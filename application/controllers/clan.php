<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Clan extends CI_Controller {
    
    function index() {
        if (ENVIRONMENT == 'production') {
            $this->under_construction();
        } else {
            if ($user = $this->ghendetta->current_user()) {
                $this->load->model('clan_model');
                $members = $this->clan_model->get_members($user['clanid']);
                $clan = $this->clan_model->get_stats($user['clanid']);
                
                $this->load->view('clan', array('members' => $members, 'clan' => $clan, 'user' => $user));
            } else {
                redirect();
            }
        }
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
        $data['offset'] = 88;
        $data['start'] = strtotime('29.03.2012 16:32');
        $data['release'] = strtotime('30.03.2012 14:00');
        $data['feature'] = 'who is in your clan';
        $data['clan'] = $clan;
        
        // view
        $this->load->view('construction', $data);
    }

}
