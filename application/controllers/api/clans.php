<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once (APPPATH . 'core/API_Controller.php');

class Clans extends API_Controller {
    
    function index() {
        $this->load->model('clan_model');
        $clans = $this->clan_model->get_all_stats();
        
        $this->output($clans);
    }
    
    function get($id = FALSE) {
        if (!$id) {
            $this->error('No ID found', 400);
        }
        
        $this->load->model('clan_model');
        $clan = $this->clan_model->get_stats($id);
        
        if ($clan) {
            $this->output($clan);
        } else {
            $this->error('Clan not found', 404);
        }
    }

}
