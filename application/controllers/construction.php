<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Construction extends CI_Controller {
    
    public function index() {
        $user = $this->ghendetta->current_user();
        
        if ($user) {
            $this->load->model('clan_model');
            $clan = $this->clan_model->get_stats($user['clanid']);
        } else {
            $clan = FALSE;
        }
        
        $this->load->view('construction', array('user' => $user, 'clan' => $clan));
    }
}
