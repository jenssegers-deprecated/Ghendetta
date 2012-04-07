<?php

class API_Controller extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        
        // load caching driver
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'dummy'));
    }
    
    function output($data) {
        set_status_header(200);
        $this->output->set_header('Content-type: application/json');
        $this->output->set_output(json_encode($data));
    }
    
    function error($message, $code = 500) {
        set_status_header($code);
        $this->output->set_header('Content-type: application/json');
        $this->output->set_output(json_encode(array('error' => $message)));
    }

}