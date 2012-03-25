<?php

class Worker extends CI_Controller {
    
    function checkins() {
        $this->load->model('user_model');
        $users = $this->user_model->get_all();
        
        foreach ($users as $user) {
            $this->ghendetta->refresh_checkins($user['fsqid'], $user['token']);
        }
    }

}