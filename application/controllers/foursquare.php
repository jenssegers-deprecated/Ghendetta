<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Foursquare extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        
        // load foursquare api
        $this->load->library('foursquare_api', '', 'foursquare');
        
        // load the foursquare adapter
        $this->load->driver('adapter', array('adapter' => 'foursquare'));
    }
    
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
            
            // fetch user ------------------------------------------------------------------------------------------
            if ($json = $this->foursquare->api('users/self')) {
                
                // convert object
                $user = $this->adapter->user($json->response->user);
                $user['token'] = $token;
                
                // insert user
                $this->load->model('user_model');
                $fsqid = $this->user_model->insert($user);
                
                // mark this user as current ghendetta user
                $this->auth->login($fsqid);
            
            } else {
                log_message('error', $this->foursquare->error);
                show_error('Something went wrong, please try again');
            }
            
            // fetch checkins ---------------------------------------------------------------------------------------
            if ($json = $this->foursquare->api('users/self/checkins', array('afterTimestamp' => (time() - 604800)))) {
                
                // convert object
                $checkins = $this->adapter->checkins($json->response->checkins->items, array('userid' => $fsqid));
                
                // insert checkins
                $this->load->model('checkin_model');
                foreach ($checkins as $checkin) {
                    $this->checkin_model->insert($checkin);
                }
            
            } else {
                log_message('error', $this->foursquare->error);
                show_error('Something went wrong, please try again');
            }
            
            // back to the homepage
            //redirect();
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
            
            // detect secret code from post
            $this->config->load('foursquare');
            if ($secret != $this->config->item('push_secret', 'foursquare')) {
                set_status_header(401);
                log_message('error', "Foursquare push used wrong secret ($secret)");
            }
            
            // save the checkin to our database
            if ($json) {
                $fsqid = $json->user->id;
                
                // check if user exists
                $this->load->model('user_model');
                if ($this->user_model->exists($fsqid)) {
                    
                    // convert object
                    $checkin = $this->adapter->checkin($json, array('userid' => $fsqid));
                    
                    // insert checkin
                    $this->load->model('checkin_model');
                    $this->checkin_model->insert($checkin);
                
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
        if ($user = $this->auth->current_user()) {
            $this->load->model('special_model');
            
            if ($code != $this->special_model->generate_code($venueid)) {
                show_error('Could not check you into this venue: wrong code');
            }
            
            // search the specific venue
            if ($venue = $this->special_model->get_active($venueid)) {
                
                // generate checkin data
                $data = array();
                $data['venueId'] = $venue['venueid'];
                
                $this->foursquare->set_token($user['token']);
                $checkin = $this->foursquare->api('checkins/add', $data, 'POST');
                
                if (!$checkin) {
                    log_message('error', $this->foursquare->error);
                    show_error('Something went wrong, please try again');
                }
                
                // convert object
                $checkin = $this->adapter->checkin($json->response->user);
                
                // insert checkin
                $this->load->model('checkin_model');
                $checkinid = $this->checkin_model->insert($checkin, array('userid' => $user['fsqid'], 'multiplier' => $venue['multiplier']));
                
                // redirect to foursquare
                redirect('https://foursquare.com/user/' . $user['fsqid'] . '/checkin/' . $checkinid);
            } else {
                redirect('https://foursquare.com/v/' . $venueid);
            }
        } else {
            $this->auth();
        }
    }

}