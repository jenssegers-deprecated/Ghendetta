<?php

class checkin_model extends CI_Model {
    
    function insert($checkin) {
        // get region leader before checkin
        $this->load->model('region_model');
        $before = $this->region_model->region_leader($checkin['regionid']);
        
        // insert checkin
        $this->db->insert('checkins', $checkin);
        $checkinid = $this->db->insert_id();
        
        // check for different region leader
        $after = $this->region_model->region_leader($checkin['regionid']);
        if ($after['clanid'] != $before['clanid']) {
            $this->region_model->update($checkin['regionid'], array('leader' => $after['clanid']));
            
            // TODO: insert notification
            // ...
        }
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