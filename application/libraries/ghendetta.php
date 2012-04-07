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
    
    private $ci, $user = FALSE;
    private $cookie_name = 'ghendetta_user';
    
    // expire time for cookie (31 days)
    private $expire = 2678400;
    
    function __construct() {
        $this->ci = &get_instance();
        
        // start up session
        session_start();
        
        // detect current user
        $this->_detect_user();
    }
    
    function login($user) {
        // generate anti-manipulation code
        $code = hash('sha256', $user . $this->ci->config->item('encryption_key'));
        
        // primary login
        $_SESSION['user'] = $user;
        $_SESSION['code'] = $code;
        
        // secondary login
        $data = serialize(array('user' => $user, 'code' => $code));
        
        $this->ci->load->library('encrypt');
        $data = $this->ci->encrypt->encode($data);
        
        $cookie = array('name' => $this->cookie_name, 'value' => $data, 'expire' => $this->expire);
        $this->ci->input->set_cookie($cookie);
        
        // set user
        $this->ci->load->model('user_model');
        $this->user = $this->ci->user_model->get($user);
    }
    
    function logout() {
        // disable primary login
        session_unset();
        session_destroy();
        
        // disable secondary login
        $cookie = array('name' => $this->cookie_name, 'value' => '', 'expire' => '');
        $this->ci->input->set_cookie($cookie);
        
        // unset user
        $this->user = FALSE;
    }
    
    function current_user() {
        return $this->user;
    }
    
    private function _detect_user() {
        if (isset($_SESSION['user']) && isset($_SESSION['code'])) {
            // detect primary login
            $user = $_SESSION['user'];
            $code = $_SESSION['code'];
        
        } else if ($data = $this->ci->input->cookie($this->cookie_name)) {
            // detect secondary login
            $this->ci->load->library('encrypt');
            $data = @unserialize($this->ci->encrypt->decode($data));
            
            if (!isset($data['user']) || !isset($data['code'])) {
                return FALSE;
            }
            
            $user = $data['user'];
            $code = $data['code'];
        } else {
            return FALSE;
        }
        
        // generate anti-manipulation code
        $check = hash('sha256', $user . $this->ci->config->item('encryption_key'));
        
        // check anti-manipulation code
        if ($code == $check) {
            // set user
            $this->ci->load->model('user_model');
            $this->user = $this->ci->user_model->get($user);
            
            // set primary login if needed
            if (!isset($_SESSION['user'])) {
                $_SESSION['user'] = $user;
                $_SESSION['code'] = $code;
            }
            
            return ($user && $user['token']);
        }
        
        return FALSE;
    }

}
