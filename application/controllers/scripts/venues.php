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
    
    function index() {
        // manual query that gets all venueid's that are not in the db
        $query = "
        	SELECT checkins.venueid
            FROM checkins
            LEFT JOIN venues ON venues.venueid = checkins.venueid
            WHERE venues.venueid is NULL LIMIT 0,10";
        
        $results = $this->db->query($query)->result_array();
        
        $this->load->library('foursquare');
        foreach($results as $result) {
            // fetch and insert venue
            $json = $this->foursquare->api('venues/'.$result['venueid']);
            $this->process_venue($json->response->venue);
        }
    }
    
    private function process_venue($venue) {
        $data = array();
        $data['venueid'] = $venue->id;
        $data['name'] = $venue->name;
        $data['lon'] = $venue->location->lng;
        $data['lat'] = $venue->location->lat;
        
        if ($venue->categories) {
            $category = reset($venue->categories);
            $data['categoryid'] = $category->id;
        }
        
        // insert venue
        $this->load->model('venue_model');
        return $this->venue_model->insert($data);
    }
}