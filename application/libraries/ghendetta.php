<?php

class Ghendetta {
    
    private $ci;
    
    function __construct() {
        $this->ci = &get_instance();
    }
    
    function set_user($fsqid) {
        $cookie = array('name' => 'ghendetta_user', 'value' => $fsqid, 'expire' => '8640000');
        return $this->ci->input->set_cookie($cookie);
    }
    
    function current_user() {
        $fsqid = $this->ci->input->cookie('ghendetta_user', TRUE);
        
        // user not detected
        if (!$fsqid) {
            return FALSE;
        }
        
        $this->ci->load->model('user_model');
        $user = $this->ci->user_model->get($fsqid);
        
        // user or token not found
        if (!$user || !$user['token']) {
            return FALSE;
        }
        
        return $user;
    }

}