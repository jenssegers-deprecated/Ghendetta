<?php

class checkin_model extends CI_Model {
    
    function insert($checkin) {
        $this->db->insert('checkins', $checkin);
        $checkinid = $this->db->insert_id();
        
        // update user points
        $this->load->model('user_model');
        $points = $this->user_model->points($checkin['userid']);
        $this->user_model->update($checkin['userid'], array('points' => $points));
    }
    
    function exists($checkinid) {
        return $this->db->where('checkinid', $checkinid)->count_all_results('checkins') != 0;
    }
    
    function get_since($userid, $since) {
        return $this->db->where('userid', $userid)->where('date >=', $since)->get('checkins')->result_array();
    }
    
    function get_unique_since($userid, $since) {
        return $this->db->where('userid', $userid)->where('date >=', $since)->group_by('venueid')->get('checkins')->result_array();
    }
    
    function last($userid) {
        return $this->db->where('userid', $userid)->order_by('date', 'desc')->get('checkins')->row_array();
    }
    
    function count($userid) {
        if ($userid) {
            return $this->db->where('userid', $userid)->count_all_results('checkins');
        } else {
            return $this->db->count_all('checkins');
        }
    }

}