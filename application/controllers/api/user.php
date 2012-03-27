<?php

class User extends CI_Controller {
    
    function index() {
        if ($user = $this->ghendetta->current_user()) {
            header('Content-type: application/json');
            
            $public = array('fsqid', 'firstname', 'lastname', 'clanid', 'picurl');
            $user = array_intersect_key($user, array_flip($public));
            
            echo json_encode($user);
        } else {
            $this->output->set_header('HTTP/1.0 401 Unauthorized');
            echo json_encode(array('error' => 'Not authenticated'));
        }
    }
    
    function fights() {
        if ($user = $this->ghendetta->current_user()) {
            $this->load->model('checkin_model');
            $checkins = $this->checkin_model->get_unique_since($user['fsqid'], time() - (608400));
            
            $this->output->set_header('Content-type: application/json');
            echo json_encode($checkins);
        } else {
            $this->output->set_header("HTTP/1.0 401 Unauthorized");
            echo json_encode(array('error' => 'Not authenticated'));
        }
    }

}