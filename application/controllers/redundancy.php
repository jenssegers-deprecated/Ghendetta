<?php

class Redundancy extends CI_Controller {
    
    function index() {
        $this->regions();
    }
    
    function regions() {
        $this->load->model('region_model');
        $regions = $this->region_model->get_all();
        
        foreach ($regions as $region) {
            $leader = $this->region_model->region_leader($region['regionid']);
            $this->region_model->update($region['regionid'], array('leader' => $leader['clanid']));
        }
    }

}