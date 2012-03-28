<?php

class Redundancy extends CI_Controller {
    
    function index() {
        $this->users();
        $this->regions();
    }
    
    function users() {
        $this->load->model('user_model');
        $users = $this->user_model->get_all();
        
        foreach ($users as $user) {
            $points = $this->user_model->points($user['fsqid']);
            $this->user_model->update($user['fsqid'], array('points' => $points));
        }
    }
    
    function regions() {
        $this->load->model('region_model');
        $regions = $this->region_model->get_all();
        
        foreach ($regions as $region) {
            $leader = $this->region_model->region_leader($region['regionid']);
            $this->region_model->update($region['regionid'], array('leader' => $leader['clanid']));
        }
    }

}