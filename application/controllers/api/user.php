<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once (APPPATH . 'core/API_Controller.php');

class User extends API_Controller {
    
    function index() {
        if ($user = $this->ghendetta->current_user()) {
            $this->load->model('user_model');
            $stats = $this->user_model->get_stats($user['fsqid']);
            
            $this->output($stats);
        } else {
            $this->error('Not authenticated', 401);
        }
    }
    
    function battles() {
        if ($user = $this->ghendetta->current_user()) {
            $this->load->model('checkin_model');
            $checkins = $this->checkin_model->get_unique_since($user['fsqid'], time() - (608400));
            
            $this->output($checkins);
        } else {
            $this->error('Not authenticated', 401);
        }
    }

}
