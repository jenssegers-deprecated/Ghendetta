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

class Users extends API_Controller {
    
    function index($id) {
        if ($user = $this->auth->current_user()) {
            $fsqid = $user['fsqid'];
            
            if ($id != $fsqid) {
                $this->error('Not implemented yet');
            }
            
            $this->load->model('user_model');
            $user = $this->user_model->get_stats($fsqid);
            
            return $user;
        } else {
            $this->error('Not authenticated', 401);
        }
    }
    
    function battles($id) {
        if ($user = $this->auth->current_user()) {
            $fsqid = $user['fsqid'];
            
            if ($id != $fsqid) {
                $this->error('Not implemented yet');
            }
            
            $this->load->model('checkin_model');
            $checkins = $this->checkin_model->get_unique_since($fsqid, (time() - 608400));
            
            return $checkins;
        } else {
            $this->error('Not authenticated', 401);
        }
    }
    
    function notifications($id) {
        if ($user = $this->auth->current_user()) {
            $fsqid = $user['fsqid'];
            
            if ($id != $fsqid) {
                $this->error('Not implemented yet');
            }
            
            $this->load->model('notification_model');
            $notifications = $this->notification_model->get_personal($fsqid);
            
            return $notifications;
        } else {
            $this->error('Not authenticated', 401);
        }
    }

}
