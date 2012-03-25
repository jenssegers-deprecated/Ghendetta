<?php

class Import extends CI_Controller {
    
    function coordinates() {
        
        $url = "http://data.appsforghent.be/poi/kotzones.json";
        $json = $this->_datatank($url);
        $this->load->model('regions_model');
        
        $this->db->truncate("regions");
        $this->db->truncate("regioncoords");
        
        foreach ($json->kotzones as $kotzone) {
            
            //kotzone in regions steken
            $region = array("name" => $kotzone->kotzone_na);
            
            $this->regions_model->insertRegion($region);
            
            $regionid = $this->db->insert_id();
            
            //coordinaten uit json veld halen
            preg_match_all('#([0-9]+\.[0-9]+),([0-9]+\.[0-9]+)#', $kotzone->coords, $matches);
            
            //coordinaten als array voorstellen en omwisselen voor google maps api
            $coords = array();
            foreach ($matches[0] as $key => $match) {
                $coords = array('regionid' => $regionid, 'lon' => $matches[2][$key], 'lat' => $matches[1][$key]);
                $this->regions_model->insertCoords($coords);
            }
        
        }
        echo "score! all regions imported";
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

?>
