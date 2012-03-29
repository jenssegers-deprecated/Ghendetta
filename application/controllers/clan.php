<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Clan extends CI_Controller {
    
    function index() {
        $this->under_construction();
    }
    
    function under_construction() {
        // this is no controller where unregistered users are allowed to hang out
        $user = $this->ghendetta->current_user();
        if (!$user) {
            redirect();
        }
        
        $this->load->model('clan_model');
        $clan = $this->clan_model->get_stats($user['clanid']);
        
        // setting data
        $data = array();
        $data['offset'] = 60;
        $data['start'] = strtotime('29.03.2012 01:15');
        $data['release'] = strtotime('29.03.2012 23:15');
        $data['feature'] = 'who is in your clan';
        $data['clan'] = $clan;
        
        // view
        $this->load->view('construction', $data);
    }

}
