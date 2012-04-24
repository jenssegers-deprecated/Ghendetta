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
        if ($user = $this->auth->current_user()) {
            $fsqid = $user['fsqid'];
            
            // try from cache
            if (!$user = $this->cache->get("api/user-$fsqid.cache")) {
                // cache miss
                $this->load->model('user_model');
                $user = $this->user_model->get_stats($fsqid);
                
                // save cache
                $this->cache->save("api/user-$fsqid.cache", $user, 300);
            }
            
            $this->output($user);
        } else {
            $this->error('Not authenticated', 401);
        }
    }
    
    function battles() {
        if ($user = $this->auth->current_user()) {
            $fsqid = $user['fsqid'];
            
            // try from cache
            if (!$checkins = $this->cache->get("api/battles-$fsqid.cache")) {
                $this->load->model('checkin_model');
                $checkins = $this->checkin_model->get_unique_since($fsqid, (time() - 608400));
                
                // save cache
                $this->cache->save("api/battles-$fsqid.cache", $checkins, 60);
            }
            
            $this->output($checkins);
        } else {
            $this->error('Not authenticated', 401);
        }
    }
    
    function notifications() {
        if ($user = $this->auth->current_user()) {
            $fsqid = $user['fsqid'];
            
            // try from cache
            if (!$notifications = $this->cache->get("api/notifications-$fsqid.cache")) {
                $this->load->model('notification_model');
                $notifications = $this->notification_model->get_personal($fsqid);
                
                // save cache
                $this->cache->save("api/notifications-$fsqid.cache", $notifications, 60);
            }
            
            $this->output($notifications);
        } else {
            $this->error('Not authenticated', 401);
        }
    }

}
