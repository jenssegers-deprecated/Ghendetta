<?php

class Import extends CI_Controller {
    
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