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
                $this->ghendetta->login($fsqid);
            
            } else {
                log_message('error', $this->foursquare->error);
                show_error('Something went wrong, please try again');
            }
            
            // fetch checkins
            if ($json = $this->foursquare->api('users/self/checkins', array('afterTimestamp' => (time() - 604800)))) {
                // insert the checkins in our database
                $this->process_checkins($json->response->checkins->items, array('userid' => $fsqid));
            } else {
                log_message('error', $this->foursquare->error);
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
                log_message('error', "Foursquare push used wrong secret ($secret)");
            }
            
            // save the checkin to our database
            if ($json) {
                $this->load->model('user_model');
                $fsqid = $json->user->id;
                
                if ($this->user_model->exists($fsqid)) {
                    $this->process_checkin($json);
                } else {
                    set_status_header(500);
                    log_message('error', "Foursquare push for unexisting user ($fsqid)");
                }
            } else {
                set_status_header(500);
                log_message('error', 'Foursquare push did not contain checkin');
            }
        } else {
            set_status_header(500);
            log_message('error', 'Foursquare push did not contain checkin');
        }
    }
    
    function checkin($venueid, $code = '') {
        if ($user = $this->ghendetta->current_user()) {
            $this->load->model('venue_model');
            
            if ($code != $this->venue_model->generate_code($venueid)) {
                show_error('Could not check you into this venue: wrong code');
            }
            
            // search the specific venue
            if ($venue = $this->venue_model->get_active($venueid)) {
                // do checkin
                $data = array();
                $data['venueId'] = $venue['venueid'];
                
                $this->foursquare->set_token($user['token']);
                $checkin = $this->foursquare->api('checkins/add', $data, 'POST');
                
                if (!$checkin) {
                    log_message('error', $this->foursquare->error);
                    show_error('Something went wrong, please try again');
                }
                
                // insert checkin response with multiplier
                $checkinid = $this->process_checkin($checkin->response->checkin, array('userid' => $user['fsqid'], 'multiplier' => $venue['multiplier']));
                
                // redirect to foursquare
                redirect('https://foursquare.com/user/' . $user['fsqid'] . '/checkin/' . $checkinid);
            } else {
                redirect( 'https://foursquare.com/v/' . $venueid );
                // show_error('Could not check you into this venue: unlisted or expired');
            }
        } else {
            redirect();
        }
    }
    
    /**
     * Cronjob controller
     */
    function cronjob() {
        // not a CLI reqeuest, check if admin
        if (!$this->input->is_cli_request()) {
            // no user detected or not admin
            if (!$user = $this->ghendetta->current_user() || !$user['admin']) {
                show_error('You have not permission to access this page');
            }
        }
        
        $limit = $this->input->get('limit') ? $this->input->get('limit') : FALSE;
        $user = $this->input->get('user') ? $this->input->get('user') : FALSE;
        
        // turn on profiler when not a CLI request
        if (!$this->input->is_cli_request()) {
            $this->output->enable_profiler(TRUE);
        } else {
            echo "Cronjob started at " . date('d/m/Y H:i') . "\n";
        }
        
        // count updated users
        $count = 0;
        
        $this->load->model('user_model');
        if (!$user) {
            $users = $this->user_model->get_all_rand($limit);
        } else {
            $user = $this->user_model->get($user);
            $users = $user ? array($user) : array();
        }
        
        foreach ($users as $user) {
            if ($this->refresh($user['fsqid'], $user['token'])) {
                $count++;
            }
        }
        
        // turn on profiler when not a CLI request
        if (!$this->input->is_cli_request()) {
            $this->output->set_profiler_sections(array('queries' => TRUE));
        } else {
            echo "Cronjob updated $count/" . count($users) . " users at " . date('d/m/Y H:i') . "\n";
            echo "---------------------------------------------------------\n";
        }
    }
    
    /**
     * User refresh method
     * @param int $fsqid
     * @param string token
     * @param int $since
     */
    private function refresh($fsqid, $token) {
        // set this user's token
        $this->foursquare->set_token($token);
        
        // the default time ago to get checkins
        $since = time() - 604800;
        
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
     * @param object $checkin
     */
    private function process_checkin($checkin, $defaults = array()) {
        $this->load->model('checkin_model');
        
        // only process this checkin if it is not already inserted in the database
        if (!$this->checkin_model->exists($checkin->id)) {
            $this->load->model('region_model');
            
            if (isset($checkin->venue) && isset($checkin->venue->location->lng) && isset($checkin->venue->location->lat)) {
                $found_region = FALSE;
                $lon = $checkin->venue->location->lng;
                $lat = $checkin->venue->location->lat;
                
                // check what region the checkin was located in, using point in polygon algorithm
                $found_region = $this->region_model->detect_region($lat, $lon);
                
                // if region is not found, the checkin is outside our territory
                if ($found_region) {
                    $data = $defaults;
                    
                    if (!isset($data['userid'])) {
                        $data['userid'] = $checkin->user->id;
                    }
                    
                    $data['checkinid'] = $checkin->id;
                    $data['date'] = $checkin->createdAt;
                    $data['venueid'] = $checkin->venue->id;
                    $data['lon'] = $checkin->venue->location->lng;
                    $data['lat'] = $checkin->venue->location->lat;
                    $data['regionid'] = $found_region['regionid'];
                    
                    if ($checkin->venue->categories) {
                        $category = reset($checkin->venue->categories);
                        $data['categoryid'] = $category->id;
                    }
                    
                    if (isset($checkin->shout)) {
                        $data['message'] = $checkin->shout;
                    }
                    
                    return $this->checkin_model->insert($data);
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
    private function process_checkins($checkins, $defaults = array()) {
        // sort checkins
        usort($checkins, array($this, 'cmp_checkins'));
        
        foreach ($checkins as &$checkin) {
            $this->process_checkin($checkin, $defaults);
        }
    }
    
    /**
     * Process a user from the Foursquare API, if a token is supplied this 
     * will overwrite the old token in the datbaase.
     * @param object $user
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
        
        // if a token is supplied, replace old token with this token and set active
        if ($token) {
            $data['token'] = $token;
            $data['active'] = 1;
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
    
    /**
     * Sorts checkins based on their created timestamp
     * @param checkin $a
     * @param checkin $b
     * @return number
     */
    private function cmp_checkins($a, $b) {
        if ($a->createdAt == $b->createdAt) {
            return 0;
        }
        return $a->createdAt < $b->createdAt ? -1 : 1;
    }

}
