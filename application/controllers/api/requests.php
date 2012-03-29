<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once (APPPATH . 'core/API_Controller.php');

class Requests extends API_Controller {
    
    function index() {
        $this->get('REGULAR');
    }
    
    function get($type) {
        $this->load->model('request_model');
        $requests = $this->request_model->get_daily($type);
        
        $this->output($requests);
    }
    
    function _remap($method) {
        switch ($method) {
            case 'index' :
                $this->index();
                break;
            default :
                $this->get($method);
        }
    }
}