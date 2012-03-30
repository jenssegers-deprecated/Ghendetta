<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once (APPPATH . 'core/API_Controller.php');

class Stats extends API_Controller {
    
    function index() {
        $this->load->model('user_model');
        $this->load->model('checkin_model');
        $this->load->model('region_model');
        $this->load->model('clan_model');
        $this->load->model('request_model');
        
        $data = array();
        $data['users'] = $this->user_model->count();
        $data['battles'] = $this->checkin_model->count();
        $data['regions'] = $this->region_model->count();
        $data['clans'] = $this->clan_model->count();
        $data['requests'] = $this->request_model->count();
        
        $this->output($data);
    }
    
}