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
    
    function get($id) {
        $this->load->model('clan_model');
        $clan = $this->clan_model->get_stats($id);
        
        if ($clan) {
            $this->output($clan);
        } else {
            $this->error('Clan not found', 404);
        }
    }
    
    function _remap($method) {
        switch ($method) {
            case 'index' :
                $this->index();
                break;
            default :
                $this->get($method);
        }
    }

}
