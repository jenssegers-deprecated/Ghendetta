<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once (APPPATH . 'core/API_Controller.php');

class Requests extends API_Controller {
    
    function index() {
        $this->load->model('request_model');
        $requests = $this->request_model->get_daily();
        
        $this->output($requests);
    }
    
}