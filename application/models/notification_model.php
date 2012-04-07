<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class notification_model extends CI_Model {
    
    function insert($notification) {
        $notification['data'] = @serialize($notification['data']);
        
        $notification['date'] = time();
        
        $this->db->insert('notifications', $notification);
        return $this->db->insert_id();
    }
    
    function update($notificationid, $notification) {
        if (isset($notification['data'])) {
            $notification['data'] = @serialize($notification['data']);
        }
        
        return $this->db->where('notificationid', $notificationid)->update('notifications', $notification);
    }
    
    function get($notificationid) {
        $notification = $this->db->where('notificationid', $notificationid)->get('notifications')->row_array();
        $notification['data'] = @unserialize($notification['data']);
        
        return $notification;
    }
    
    function get_personal($userid, $clanid = FALSE, $limit = FALSE) {
        if (!$clanid) {
            $this->load->model('user_model');
            $user = $this->user_model->get($userid);
            $clanid = $user['clanid'];
        }
        
        $query = "
        	SELECT notificationid, type, date, data
        	FROM notifications
        	WHERE `to` =
        		CASE `to_type`
        			WHEN 'user' THEN ?
        			WHEN 'clan' THEN ?
        		END
        	ORDER BY date DESC
        	";
        
        $notifications = $this->db->query($query, array($userid, $clanid))->result_array();
        foreach ($notifications as &$notification) {
            $notification['data'] = @unserialize($notification['data']);
        }
        
        return $notifications;
    }
    
    function count() {
        return $this->db->count_all('notifications');
    }

}
