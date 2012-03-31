<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ghendetta {
    
    private $ci;
    
    function __construct() {
        $this->ci = &get_instance();
    }
    
    function set_user($fsqid) {
        $code = hash('sha256', $fsqid . $this->ci->config->item('encryption_key'));
        $data = serialize(array('id' => $fsqid, 'code' => $code));
        
        $this->ci->load->library('encrypt');
        $data = $this->ci->encrypt->encode($data);
        
        $cookie = array('name' => 'ghendetta_user', 'value' => $data, 'expire' => '8640000');
        return $this->ci->input->set_cookie($cookie);
    }
    
    function current_user() {
        $data = $this->ci->input->cookie('ghendetta_user', TRUE);
        
        // cookie not found
        if (!$data) {
            return FALSE;
        }
        
        $this->ci->load->library('encrypt');
        $data = @unserialize($this->ci->encrypt->decode($data));
        
        // wrong cookie data
        if (!isset($data['id']) || !isset($data['code'])) {
            return FALSE;
        }
        
        // check code
        $code = hash('sha256', $data['id'] . $this->ci->config->item('encryption_key'));
        if ($code != $data['code']) {
            return FALSE;
        }
        
        $this->ci->load->model('user_model');
        $user = $this->ci->user_model->get($data['id']);
        
        // user or token not found
        if (!$user || !$user['token']) {
            return FALSE;
        }
        
        return $user;
    }

}