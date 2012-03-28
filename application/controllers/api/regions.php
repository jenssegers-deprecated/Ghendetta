<?php

class Regions extends CI_Controller {
    
    function index() {
        header('HTTP/1.1 200 OK');
        $this->load->model('region_model');
        $regions = $this->region_model->region_stats();
        
        header('Content-type: application/json');
        echo json_encode($regions);
    }

}
