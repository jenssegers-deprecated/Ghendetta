<?php

class request_model extends CI_Model {
	
	function insert($request) {
		$this->db->insert('requests', $request);
		return $this->db->insert_id();
	}
	
}