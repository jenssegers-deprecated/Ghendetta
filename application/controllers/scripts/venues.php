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
            LEFT JOIN venues ON checkins.venueid = venues.venueid
            WHERE venues.venueid is NULL
            " . ($limit ? "LIMIT 0,$limit" : "");
        
        $results = $this->db->query($query)->result_array();
        
        $count = 0;
        $this->load->model('venue_model');
        
        foreach ($results as $result) {
            // fetch and insert venue
            $json = $this->foursquare->api('venues/' . $result['venueid']);
            
            if ($json) {
                $venue = $this->adapter->venue($json->response->venue);
                if ($this->venue_model->insert($venue)) {
                    $count++;
                    echo "Inserted #" . $venue['venueid'] . ": " . $venue['name'] . "\n";
                } else {
                    echo "Could not insert #" . $venue['venueid'] . ": " . $venue['name'] . "\n";
                }
            } else {
                echo $this->foursquare->error . "\n";
            }
        }
        
        echo "Inserted $count venues\n";
        
        if (!$this->input->is_cli_request()) {
            $this->output->set_profiler_sections(array('queries' => TRUE));
        }
    }
}