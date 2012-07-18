<?php

class MY_Controller extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        
        if($user = $this->auth->current_user()) {
            // update last_visit
            $this->load->model('user_model');
            $this->user_model->update($user['fsqid'], array('last_visit' => time()));
        }
    }

}