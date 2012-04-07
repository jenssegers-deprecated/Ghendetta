<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends CI_Controller {
    
    function index() {
        $user = $this->ghendetta->current_user();
        redirect('admin/user/checkins/' . $user['fsqid']);
    }
    
    function checkins($userid) {
        $this->load->model('checkin_model');
        $checkins = $this->checkin_model->get_hourly($userid, 5);
        
        $this->load->view('admin/checkins', array('checkins' => $checkins));
    }

}
