<?php

class Map extends CI_Controller {
    
    function index() {
        $json = $this->_datatank('http://data.appsforghent.be/poi/deelgemeentengent.json');
        
        $gemeentes = array();
        
        foreach ($json->deelgemeentengent as $gemeente) {
            // coordinaten uit json veld halen
            preg_match_all('#([0-9]+\.[0-9]+),([0-9]+\.[0-9]+)#', $gemeente->coords, $matches);
            
            // coordinaten als array voorstellen en omwisselen voor google maps api
            $coords = array();
            foreach ($matches[0] as $key => $match) {
                $coords[] = array($matches[2][$key], $matches[1][$key]);
            }
            
            $gemeentes[$gemeente->dgem] = $coords;
        }
        
        $this->load->view('map', array('gemeentes' => $gemeentes));
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