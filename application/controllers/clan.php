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
        $data['offset'] = 87;
        $data['start'] = strtotime('29.03.2012 16:32');
        $data['release'] = strtotime('30.03.2012 11:00');
        $data['feature'] = 'who is in your clan';
        $data['clan'] = $clan;
        
        // view
        $this->load->view('construction', $data);
    }

}
