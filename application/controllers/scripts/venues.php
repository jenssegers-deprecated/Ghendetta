<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Venues extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        
        // load foursquare api
        $this->load->library('foursquare_api', '', 'foursquare');
        
        $user = $this->auth->current_user();
        if (!($user && $user['admin']) && !$this->input->is_cli_request()) {
            show_error('You have not permission to access this page');
        }
    }
    
    function index($limit = FALSE) {
        if (!$this->input->is_cli_request()) {
            $this->output->enable_profiler(TRUE);
        }
        
        // manual query that gets all venueid's that are not in the db
        $query = "
        	SELECT checkins.venueid
            FROM checkins
            LEFT JOIN venues ON venues.venueid = checkins.venueid
            WHERE venues.venueid is NULL
            ORDER BY RAND() LIMIT 1";
        
        $count = 0;
        $this->load->model('venue_model');
        
        while ($row = $this->db->query($query)->row_array()) {
            // fetch and insert venue
            $json = $this->foursquare->api('venues/' . $row['venueid']);
            $venue = $this->adapter->venue($json->response->venue);
            $this->venue_model->insert($venue);
            
            $count++;
            
            if ($limit && $count >= $limit) {
                break;
            }
        }
        
        if (!$this->input->is_cli_request()) {
            $this->output->set_profiler_sections(array('queries' => TRUE));
        }
    }
}