<?php

class User extends CI_Controller {
    
    function index() {
        if ($user = $this->ghendetta->current_user()) {
            header('Content-type: application/json');
            
            $this->load->model('user_model');
            echo json_encode($this->user_model->user_stats($user['fsqid']));
        } else {
            $this->output->set_header('HTTP/1.1 401 Unauthorized');
            echo json_encode(array('error' => 'Not authenticated'));
        }
    }
    
    function fights() {
        if ($user = $this->ghendetta->current_user()) {
            $this->load->model('checkin_model');
            $checkins = $this->checkin_model->get_unique_since($user['fsqid'], time() - (608400));
            
            header('HTTP/1.1 200 OK');
            $this->output->set_header('Content-type: application/json');
            echo json_encode($checkins);
        } else {
            $this->output->set_header("HTTP/1.1 401 Unauthorized");
            echo json_encode(array('error' => 'Not authenticated'));
        }
    }

}
