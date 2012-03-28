<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once (APPPATH . 'core/API_Controller.php');

class Regions extends API_Controller {
    
    function index() {
        $this->load->model('region_model');
        $regions = $this->region_model->all_region_stats();
        
        $this->output($regions);
    }
    
    function get($id = FALSE) {
        if (!$id) {
            $this->error('No ID found', 400);
        }
        
        $this->load->model('region_model');
        $region = $this->region_model->region_stats($id);
        
        if ($region) {
            $this->output($region);
        } else {
            $this->error('Region not found', 404);
        }
    }

}
