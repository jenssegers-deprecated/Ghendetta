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
        
        if (!$this->input->is_cli_request()) {
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
            
            $this->load->model('venue_model');
            $this->venue_model->insert_list($list);
            
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
        $category = reset($venue->categories);
        
        $data = array();
        $data['listid'] = $listid;
        $data['venueid'] = $venue->id;
        $data['name'] = $venue->name;
        $data['categoryid'] = $category->id;
        $data['lon'] = $venue->location->lng;
        $data['lat'] = $venue->location->lat;
        
        if ($regionid = $this->region_model->detect_region($data['lat'], $data['lon'])) {
            $data['regionid'] = $regionid;
            return $this->venue_model->insert($data);
        }
        
        return FALSE;
    }
}