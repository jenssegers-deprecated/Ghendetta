<?php

class request_model extends CI_Model {
	
	function insert($request) {
		return $this->db->insert('requests', $request);
	}
	
}