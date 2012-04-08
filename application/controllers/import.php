<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Import extends CI_Controller {
    
    function categories() {
        $json = $this->foursquare->api('venues/categories');
        
        $this->load->model('category_model');
        $this->category_model->truncate();
        
        foreach($json->response->categories as $category) {
            $this->process_category($category);
        }
    }
    
    private function process_category($category, $parent = '') {
        $data = array();
        $data['categoryid'] = $category->id;
        $data['name'] = $category->name;
        $data['icon'] = $category->icon;
        $data['parent'] = $parent;
        
        $this->category_model->insert($data);
        
        // recursive child categories
        if(isset($category->categories) && count($category->categories)) {
            foreach($category->categories as $subcategory) {
                $this->process_category($subcategory, $category->id);
            }
        }
    }

    function venuelist( $listid, $code = FALSE ){
        
        $this->config->load('foursquare', TRUE);
        $check = $this->config->item('cronjob_code', 'foursquare');
        
        if( $code != $check ){
            show_error('You don\'t have permission to access this page');
        }
        
        //get parameters
        $startdate = $this->input->get('from') ? $this->input->get('from') : time() ;
        $enddate = $this->input->get('till') ? $this->input->get('till') : time() ;
        $multiplier = $this->input->get('multi') ? $this->input->get('multi') : 2 ; //default = 2
        
        if ($json = $this->foursquare->api('lists/' . $listid )) {

            $list = array();
            $list['startdate'] = $startdate ;
            $list['enddate'] = $enddate ;
            $list['multiplier'] = $multiplier ;
            $list['listid'] = $listid ;
            $list['name'] = $json->response->list->name ;
            
            $this->load->model('venue_model');
            $this->venue_model->insert_list( $list );
            
            if( $list = $json->response->list->listItems->items ){
                $venuedata = array();
                foreach(  $list as $venue ){
                    $venue = $venue->venue ;
                    
                    $venuedata['listid'] = $listid ;
                    $venuedata['venueid'] = $venue->id ;
                    $venuedata['name'] = $venue->name ;
                    $venuedata['categoryid'] = $venue->categories[0]->id ;
                    $venuedata['lon'] = $venue->location->lat ;
                    $venuedata['lat'] = $venue->location->lng ;
                    
                    $this->venue_model->insert( $venuedata );
                }
                
                echo count( $list ) . ' venues from list ' . $listid . ' imported.' ;
            }else{
                show_error('This list doesn\'t exist');
            }
            
        }
        echo $this->foursquare->error ;
    }
    
    function coordinates() {
        $url = 'http://data.appsforghent.be/poi/kotzones.json';
        $json = $this->_datatank($url);
        
        $this->load->model('region_model');
        $this->region_model->truncate();
        
        foreach ($json->kotzones as $kotzone) {
            $regionid = $this->region_model->insert_region(array('name' => $kotzone->kotzone_na));
            
            // get coordinates
            preg_match_all('#([0-9]+\.[0-9]+),([0-9]+\.[0-9]+)#', $kotzone->coords, $matches);
            
            // put coordinates in array
            $coords = array();
            foreach ($matches[0] as $key => $match) {
                $coords = array('regionid' => $regionid, 'lon' => $matches[2][$key], 'lat' => $matches[1][$key]);
                $this->region_model->insert_roords($coords);
            }
        }
    }
    
    function _datatank($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPGET, TRUE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        
        $data = curl_exec($curl);
        curl_close($curl);
        
        return json_decode($data);
    }
}
