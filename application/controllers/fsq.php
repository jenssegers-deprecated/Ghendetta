<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class FSQ extends CI_Controller {
    
    // store regions here
    private $regions = NULL;
    
    function index() {
        $this->auth();
    }
    
    /**
     * Start OAuth authentication
     */
    function auth() {
        redirect($this->foursquare->auth_url());
    }
    
    /**
     * OAuth callback controller
     */
    function callback() {
        if ($code = $this->input->get('code')) {
            // request token
            $token = $this->foursquare->request_token($code);
            
            // fetch user
            if ($json = $this->foursquare->api('users/self')) {
                $fsqid = $json->response->user->id;
                
                // update the user in our database
                $this->process_user($json->response->user, $token);
                
                // mark this user as current ghendetta user
                $this->ghendetta->set_user($fsqid);
            } else {
                show_error('Something went wrong, please try again');
            }
            
            // fetch checkins
            if ($json = $this->foursquare->api('users/self/checkins', array('afterTimestamp' => (time() - 604800)))) {
                // insert the checkins in our database
                $this->process_checkins($json->response->checkins->items, $fsqid);
            } else {
                show_error('Something went wrong, please try again');
            }
            
            // back to the homepage
            redirect();
        } else {
            show_error('Something went wrong');
        }
    }
    
    /**
     * Foursquare Push API controller
     */
    function push() {
        if ($this->input->post('checkin')) {
            $json = json_decode($this->input->post('checkin'));
            $secret = $this->input->post('secret');
            
            $this->config->load('foursquare');
            if ($secret != $this->config->item('push_secret', 'foursquare')) {
                set_status_header(401);
                log_message('error', 'Foursquare push used wrong secret');
            }
            
            // save the checkin to our database
            if ($json) {
                $this->process_checkin($json);
            } else {
                set_status_header(400);
                log_message('error', 'Foursquare push did not contain checkin');
            }
        } else {
            set_status_header(400);
            log_message('error', 'Foursquare push did not contain checkin');
        }
    }
    
    /**
     * Cronjob controller
     */
    function cronjob($code, $limit = FALSE) {
        $this->output->enable_profiler(TRUE);
        
        $this->config->load('foursquare', TRUE);
        $check = $this->config->item('cronjob_code', 'foursquare');
        
        if ($code != $check) {
            show_error('You have not permission to access this page');
        }
        
        $this->load->model('user_model');
        $users = $this->user_model->get_all_rand($limit);
        
        foreach ($users as $user) {
            //echo "Updating user " . $user['fsqid'] . "\n";
            $this->refresh($user['fsqid'], $user['token'], $user['registered']);
        }
        
        $this->output->set_profiler_sections(array('queries' => TRUE));
    }
    
    /**
     * User refresh method
     * @param int $fsqid
     * @param string token
     * @param int $since
     */
    private function refresh($fsqid, $token, $since = FALSE) {
        // set this user's token
        $this->foursquare->set_token($token);
        
        // the default time ago to get checkins
        if (!$since || $since < (time() - 604800)) {
            $since = time() - 604800;
        }
        
        // get the last checkin of this user
        $this->load->model('checkin_model');
        $last = $this->checkin_model->get_last($fsqid);
        
        // only request the smallest timespan
        if ($last) {
            // we substract 1 hour just in case we missed some checkins
            $since = max($since, ($last['date'] - 3600));
        }
        
        // fetch checkins
        if ($json = $this->foursquare->api('users/' . $fsqid . '/checkins', array('afterTimestamp' => $since))) {
            // insert the checkins in our database
            $this->process_checkins($json->response->checkins->items, $fsqid);
        } else {
            return FALSE;
        }
        
        return TRUE;
    }
    
    /**
     * Process a single checkin from the Foursquare Push API
     * @param Object $checkin
     */
    private function process_checkin($checkin) {
        $this->load->model('checkin_model');
        
        // only process this checkin if it is not already inserted in the database
        if (!$this->checkin_model->exists($checkin->id)) {
            $this->load->model('region_model');
            $this->load->helper('polygon');
            
            if(is_null($this->regions)) {
                $this->regions = $this->region_model->get_all();
            }
            
            if (isset($checkin->venue) && isset($checkin->venue->location->lng) && isset($checkin->venue->location->lat)) {
                $found_region = FALSE;
                $lon = $checkin->venue->location->lng;
                $lat = $checkin->venue->location->lat;
                
                // check what region the checkin was located in, using point in polygon algorithm
                foreach ($this->regions as $region) {
                    if (is_in_polygon($region['coords'], $lon, $lat)) {
                        $found_region = $region;
                        break; // yes this is a break :)
                    }
                }
                
                // if region is not found, the checkin is outside our territory
                if ($found_region) {
                    $data = array();
                    $data['checkinid'] = $checkin->id;
                    $data['userid'] = $checkin->user->id;
                    $data['date'] = $checkin->createdAt;
                    $data['venueid'] = $checkin->venue->id;
                    $data['lon'] = $checkin->venue->location->lng;
                    $data['lat'] = $checkin->venue->location->lat;
                    $data['regionid'] = $found_region['regionid'];
                    
                    $this->checkin_model->insert($data);
                    return TRUE;
                }
            }
        }
        
        return FALSE;
    }
    
    /**
     * Process an array of checkins from the Foursquare API
     * @param array $checkins
     * @param int $userid
     */
    private function process_checkins($checkins, $userid) {
        // sort checkins
        usort($checkins, array($this, 'cmp_checkins'));
        
        foreach ($checkins as &$checkin) {
            // to process a singe checkin we need to add a user value to the checkin
            if (!isset($checkin->user)) {
                $checkin->user = new stdClass();
                $checkin->user->id = $userid;
            }
            
            $this->process_checkin($checkin);
        }
    }
    
    /**
     * Process a user from the Foursquare API, if a token is supplied this 
     * will overwrite the old token in the datbaase.
     * @param Object $user
     * @param string $token
     */
    private function process_user($user, $token = FALSE) {
        $this->load->model('user_model');
        
        $data = array();
        $data['fsqid'] = $user->id;
        $data['firstname'] = $user->firstName;
        $data['lastname'] = isset($user->lastName) ? $user->lastName : '';
        $data['email'] = isset($user->contact->email) ? $user->contact->email : '';
        $data['twitter'] = isset($user->contact->twitter) ? $user->contact->twitter : '';
        $data['picurl'] = isset($user->photo) ? $user->photo : '';
        
        // if a token is supplied, replace old token with this token
        if ($token) {
            $data['token'] = $token;
        }
        
        // insert or update this user
        if (!$this->user_model->exists($data['fsqid'])) {
            // get clan suggestion
            $this->load->model('clan_model');
            $clan = $this->clan_model->suggest_clan();
            $data['clanid'] = $clan['clanid'];
            
            $this->user_model->insert($data);
        } else {
            $this->user_model->update($data['fsqid'], $data);
        }
    }
    
    private function cmp_checkins($a, $b) {
        if ($a->createdAt == $b->createdAt) {
            return 0;
        }
        return $a->createdAt < $b->createdAt ? -1 : 1;
    }

}
