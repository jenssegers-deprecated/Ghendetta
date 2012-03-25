<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Main extends CI_Controller {
    
    public function index() {
        $this->load->model('region_model');
        $battlefield = $this->region_model->battlefield();
        
        $this->load->view('map', array('battlefield' => $battlefield));
    }
}
