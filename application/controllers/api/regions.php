<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once (APPPATH . 'core/API_Controller.php');

class Regions extends API_Controller {
    
    function index() {
        $this->load->model('region_model');
        $regions = $this->region_model->region_stats();
        
        $this->output($regions);
    }

}
