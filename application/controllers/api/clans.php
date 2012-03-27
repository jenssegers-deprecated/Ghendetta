<?php

class Clans extends CI_Controller {
    
    function index() {
        $this->load->model('clan_model');
        $clans = $this->clan_model->get_all();
        
        $this->output->set_header('Content-type: application/json');
        echo json_encode($clans);
    }
    
    function get($id = FALSE) {
        if (!$id) {
            $this->output->set_header('HTTP/1.0 400 Bad Request');
            echo json_encode(array('error' => 'Bad request'));
        }
        
        $this->load->model('clan_model');
        $clan = $this->clan_model->get($id);
        
        if ($clan) {
            $this->output->set_header('Content-type: application/json');
            echo json_encode($clan);
        } else {
            $this->output->set_header('HTTP/1.0 404 Not Found');
            echo json_encode(array('error' => 'Clan not found'));
        }
    }

}