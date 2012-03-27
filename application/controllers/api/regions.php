<?php

class Regions extends CI_Controller {
    
    function index() {
        $this->load->model('region_model');
        $regions = $this->region_model->battlefield();
        
        header('Content-type: application/json');
        echo json_encode($regions);
    }
    
}