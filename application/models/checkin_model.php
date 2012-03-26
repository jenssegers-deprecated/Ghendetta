<?php

class checkin_model extends CI_Model {
	
	function insert($user) {
		$this->db->insert('checkins', $user);
		return $this->db->insert_id();
	}
	
	function exists($checkinid) {
		return $this->db->where('checkinid', $checkinid)->count_all_results('checkins') != 0;
	}
	
	function get_since($user, $since) {
	    return $this->db->where('userid', $user)->where('date >=', $since)->get('checkins')->result_array();
	}
	
    function get_unique_since($user, $since) {
	    return $this->db->where('userid', $user)->where('date >=', $since)->group_by('venueid')->get('checkins')->result_array();
	}
	
	function last($user) {
	    return $this->db->where('userid', $user)->order_by('date', 'desc')->get('checkins')->row_array();
	}
	
}