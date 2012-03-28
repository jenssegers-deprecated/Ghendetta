<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    
class Clan extends CI_Controller {
    
    function index() {
        $this->load->model('clan_model');
        
        $user = $this->ghendetta->current_user();
        if (!$user) {
            redirect();
        }
        
        $data = array();
        $data['user'] = $user;
        $data['clan'] = $this->clan_model->get($user['clanid']);
        $data['clanmembers'] = $this->clan_model->get_members($user['clanid']);
        
        $this->load->view('clan', $data);
    }
    
    function save() {
    
    }

}