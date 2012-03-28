<?php

class user_model extends CI_Model {
    
    function get($fsqid) {
        return $this->db->where('fsqid', $fsqid)->get('users')->row_array();
    }
    
    function user_stats($fsqid) {
        return $this->db->query('
        	SELECT fsqid, firstname, lastname, picurl, count(checkins.checkinid) as points
        	FROM users
        	LEFT JOIN checkins ON users.fsqid = checkins.userid AND checkins.date >= UNIX_TIMESTAMP( subdate(now(),7) )
        	WHERE users.fsqid = ?
        	GROUP BY users.fsqid
        	', array($fsqid))->row_array();
    }
    
    function insert($user) {
        $this->db->insert('users', $user);
        return $this->db->insert_id();
    }
    
    function update($fsqid, $user) {
        return $this->db->where('fsqid', $fsqid)->update('users', $user);
    }
    
    function exists($fsqid) {
        return $this->db->where('fsqid', $fsqid)->count_all_results('users') != 0;
    }
    
    function get_all() {
        return $this->db->get('users')->result_array();
    }
    
    function count() {
        return $this->db->count_all('users');
    }

}