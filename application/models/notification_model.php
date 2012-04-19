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
    
    function get_personal($userid, $limit = FALSE) {
        $query = "
        	SELECT notifications.*, CASE WHEN date <= last_visit THEN 1 ELSE 0 END as 'read'
            FROM users
            JOIN notifications ON to_type = 'clan' AND `to` = clanid
            WHERE fsqid = ?
            
            UNION
            
            SELECT notifications.*, CASE WHEN date <= last_visit THEN 1 ELSE 0 END as 'read'
            FROM users
            JOIN notifications ON to_type = 'user' AND `to` = fsqid
            WHERE fsqid = ?
        	
            ORDER BY date DESC, notificationid DESC
            " . $limit ? "LIMIT 0,$limit" : "";
        
        $notifications = $this->db->query($query, array($userid, $userid))->result_array();
        foreach ($notifications as &$notification) {
            $notification['data'] = @unserialize($notification['data']);
        }
        
        return $notifications;
    }
    
    function count() {
        return $this->db->count_all('notifications');
    }

}
