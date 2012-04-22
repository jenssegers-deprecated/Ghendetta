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
        
    	/*$user = $this->auth->current_user();
        if (!($user && $user['admin']) && !$this->input->is_cli_request()) {
            show_error('You have not permission to access this page');
        }*/
    }
    
    function index() {
        if (!$this->input->is_cli_request()) {
            $this->output->enable_profiler(TRUE);
        }
        
        echo "Cronjob started at " . date('d/m/Y H:i') . "\n";
        
        $limit = $this->input->get('limit') ? $this->input->get('limit') : FALSE;
        $user = $this->input->get('user') ? $this->input->get('user') : FALSE;
        
        $this->load->model('user_model');
        if (!$user) {
            $users = $this->user_model->get_all_rand($limit);
        } else {
            $user = $this->user_model->get($user);
            $users = $user ? array($user) : array();
        }
        
        // count updated users
        $count = 0;
        
        $this->load->model('checkin_model');
        foreach ($users as $user) {
            
            // furthest to back in time
            $since = time() - 604800;
            
            // check if last checkin was newer
            if ($last = $this->checkin_model->get_last($user['fsqid'])) {
                $since = max($since, ($last['date'] - 3600));
            }
            
            $this->foursquare->set_token($user['token']);
            if ($json = $this->foursquare->api('users/' . $user['fsqid'] . '/checkins', array('afterTimestamp' => $since))) {
                
                // convert object
                $this->load->driver('conversion');
                $checkins = $this->conversion->foursquare->checkins($json->response->checkins->items, array('userid' => $user['fsqid']));
                
                // insert checkins
                $this->load->model('checkin_model');
                foreach ($checkins as $checkin) {
                    if ($this->checkin_model->insert($checkin)) {
                        $count++;
                    }
                }
            
            } else {
                echo $this->foursquare->error . "\n";
            }
        }
        
        echo "Cronjob inserted $count checkins for " . count($users) . " users at " . date('d/m/Y H:i') . "\n";
        echo "---------------------------------------------------------------\n";
        
        if (!$this->input->is_cli_request()) {
            $this->output->set_profiler_sections(array('queries' => TRUE));
        }
    }

}