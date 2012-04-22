<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Coordinates extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        
        $user = $this->auth->current_user();
        if (!($user && $user['admin']) && !$this->input->is_cli_request()) {
            show_error('You have not permission to access this page');
        }
    }
    
    function index() {
        $url = 'http://data.appsforghent.be/poi/kotzones.json';
        $json = $this->datatank($url);
        
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
    
    private function datatank($url) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPGET, TRUE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        
        $data = curl_exec($curl);
        curl_close($curl);
        
        return json_decode($data);
    }
}
