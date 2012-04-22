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
        
        // load the foursquare adapter
        $this->load->driver('adapter', array('adapter' => 'foursquare'));
        
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
        	SELECT DISTINCT checkins.venueid
            FROM checkins
            LEFT JOIN venues ON venues.venueid = checkins.venueid
            WHERE venues.venueid is NULL
            " . ($limit ? "LIMIT 0,$limit" : "");
        
        $results = $this->db->query($query)->result_array();
        
        $this->load->model('venue_model');
        
        foreach ($results as $result) {
            // fetch and insert venue
            $json = $this->foursquare->api('venues/' . $result['venueid']);
            $venue = $this->adapter->venue($json->response->venue);
            $this->venue_model->insert($venue);
        }
        
        if (!$this->input->is_cli_request()) {
            $this->output->set_profiler_sections(array('queries' => TRUE));
        }
    }
}