<?php

class API_Controller extends CI_Controller {
    
    protected $ttl = 60;
    
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
    
    function _remap($method_name, $args = array()) {
        // load cache driver
        $this->load->driver('cache');
        
        $id = $this->uri->uri_string();
        
        // try cached endpoint
        if (!$data = $this->cache->get($id)) {
            
            if (method_exists($this, $method_name)) {
                $data = call_user_func_array(array($this, $method_name), $args);
            } elseif (method_exists($this, 'get')) {
                $data = call_user_func_array(array($this, 'get'), $args);
            } else {
                $this->error('Unknown API endpoint');
            }
            
            if (!$data) {
                $this->error('No data returned');
            }
            
            // save cache
            $this->cache->save($id, $data, $this->ttl);
        }
        
        $this->output($data);
    }

}