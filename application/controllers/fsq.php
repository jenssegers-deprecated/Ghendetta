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
            $token = $this->foursquare->request_token($code);
            $fsqid = $this->foursquare->fsqid();
            
            // set this user as ghendetta user
            $this->ghendetta->set_user($fsqid);
            
            // start refreshing data
            $this->ghendetta->refresh_user($fsqid, $token);
            $this->ghendetta->refresh_checkins($fsqid);
            
            // back to the homepage
            redirect();
        } else {
            show_error('Something went wrong');
        }
    }
    
    /**
     * Refresh user and checkin information from foursquare
     */
    function refresh($type = '*') {
        $user = $this->ghendetta->current_user();
        
        if ($user) {
            $this->ghendetta->refresh_user($user['fsqid']);
            $this->ghendetta->refresh_checkins($user['fsqid']);
        } else {
            if ($type == 'ajax') {
                echo json_encode(array('status' => 'not authenticated'));
            } else {
                // please authenticate
                redirect('foursquare/auth');
            }
        }
        
        if ($type == 'ajax') {
            echo json_encode(array('status' => 'updated'));
        } else {
            // go back home!
            redirect();
        }
    }

}
