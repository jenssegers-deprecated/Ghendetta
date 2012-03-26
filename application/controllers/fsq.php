<?php

class fsq extends CI_Controller {
    
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
     * OAuth callback function
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
            if ($json = $this->foursquare->api('users/self/checkins', array('afterTimestamp' => (time() - 608400)))) {
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
     * AJAX refresh method
     * @param int $fsqid
     */
    function refresh($fsqid = FALSE) {
        // get current user or user with matching fsqid
        if (!$fsqid) {
            $user = $this->ghendetta->current_user();
        } else {
            $this->load->model('user_model');
            $user = $this->user_model->get($fsqid);
        }
        
        if ($user && $user['token']) {
            // set this user's token
            $this->foursquare->set_token($user['token']);
            
            // refresh user
            if ($json = $this->foursquare->api('users/' . $user['fsqid'])) {
                // update the user in our database
                $this->process_user($json->response->user);
            } else {
                echo json_encode(array('message' => $this->foursquare->error));
                return FALSE;
            }
            
            // refresh checkins
            if ($json = $this->foursquare->api('users/' . $user['fsqid'] . '/checkins', array('afterTimestamp' => (time() - 608400)))) {
                // insert the checkins in our database
                $this->process_checkins($json->response->checkins->items, $user['fsqid']);
            } else {
                echo json_encode(array('message' => $this->foursquare->error));
                return FALSE;
            }
        } else {
            echo json_encode(array('message' => 'Not authenticated'));
            return FALSE;
        }
        
        echo json_encode(array('message' => 'Ok'));
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
                show_error('Wrong secret');
            }
            
            // save the checkin to our database
            $this->process_checkin($json);
        }
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
            
            $regions = $this->region_model->get_all();
            
            $found_region = FALSE;
            $lon = $checkin->venue->location->lng;
            $lat = $checkin->venue->location->lat;
            
            // check what region the checkin was located in, using point in polygon algorithm
            foreach ($regions as $region) {
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
            }
        }
    }
    
    /**
     * Process an array of checkins from the Foursquare API
     * @param array $checkins
     * @param int $userid
     */
    private function process_checkins($checkins, $userid) {
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
        $data['picurl'] = isset($user->photo) ? $user->photo : '';
        
        // if a token is supplied, replace old token with this token
        if ($token) {
            $data['token'] = $token;
        }
        
        // insert or update this user
        if (!$this->user_model->exists($data['fsqid'])) {
            $data['clanid'] = rand(1, 4);
            $this->user_model->insert($data);
        } else {
            $this->user_model->update($data['fsqid'], $data);
        }
    }

}
