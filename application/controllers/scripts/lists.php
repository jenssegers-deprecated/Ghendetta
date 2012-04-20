<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lists extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        
        $user = $this->auth->current_user();
        if (!($user && $user['admin']) && !$this->input->is_cli_request()) {
            show_error('Scripts can only be executed from CLI');
        }
    }
    
    function index($listid, $startdate = FALSE, $enddate = FALSE, $multiplier = FALSE) {
        // default values
        $startdate = $startdate ? $startdate : time();
        $enddate = $enddate ? $enddate : time();
        
        if ($json = $this->foursquare->api("lists/$listid")) {
            $count = 0;
            
            $list = array();
            $list['startdate'] = $startdate;
            $list['enddate'] = $enddate;
            $list['listid'] = $listid;
            $list['name'] = $json->response->list->name;
            
            if ($multiplier) {
                $list['multiplier'] = $multiplier;
            }
            
            $this->load->model('list_model');
            $this->list_model->insert($list);
            
            if ($list = $json->response->list->listItems->items) {
                // process venues
                foreach ($list as $venue) {
                    if ($this->process_venue($venue->venue, $listid)) {
                        $count++;
                    }
                }
                
                echo "$count/" . count($list) . " venues imported from list $listid";
            } else {
                show_error('This list does not exist');
            }
        } else {
            show_error($this->foursquare->error);
        }
    }
    
    private function process_venue($venue, $listid) {
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
        $this->venue_model->insert($data);

        // only when valid region
        $this->load->model('region_model');
        if ($regionid = $this->region_model->detect_region($data['lat'], $data['lon'])) {
            
            $data = array();
            $data['listid'] = $listid;
            $data['venueid'] = $venue->id;
            
            // insert special
            $this->load->model('special_model');
            return $this->special_model->insert($data);
        }

        return FALSE;
    }
}