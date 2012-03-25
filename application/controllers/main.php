<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Main extends CI_Controller {
    
    public function index() {
        $this->load->model('region_model');
        $regions = $this->region_model->battlefield();
        
        $this->load->model('clan_model');
        $clans = $this->clan_model->get_all();
        
        if ($user = $this->ghendetta->current_user()) {
            $this->load->model('checkin_model');
            $checkins = $this->checkin_model->get_since($user['fsqid'], time() - (608400));
            
            $clan = $this->clan_model->get($user['clanid']);
        } else {
            $checkins = array();
            $clan = FALSE;
        }
        
        $this->load->view('map', array('regions' => $regions, 'checkins' => $checkins, 'clan' => $clan, 'clans' => $clans));
    }
}
