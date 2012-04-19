<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Main extends CI_Controller {
    
    public function index() {
        $this->load->model('clan_model');
        $clans = $this->clan_model->get_all();
        
        if ($user = $this->auth->current_user()) {
            $clan = $this->clan_model->get($user['clanid']);
            
            $this->load->model('notification_model');
            $notifications = $this->notification_model->get_personal($user['fsqid']);
        } else {
            $clan = FALSE;
            $notifications = array();
        }
        
        $this->load->view('dashboard', array('clan' => $clan, 'clans' => $clans, 'notifications' => $notifications));
    }
}
