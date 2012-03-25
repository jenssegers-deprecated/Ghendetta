<?php

class checkin_model extends CI_Model {
	
	function insert($user) {
		return $this->db->insert('checkins', $user);
	}
	
	function exists($checkinid) {
		return $this->db->where('checkinid', $checkinid)->count_all_results('checkins') != 0;
	}
	
}