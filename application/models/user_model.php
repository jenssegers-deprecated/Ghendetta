<?php

class user_model extends CI_Model {

    function get($fsqid) {
        return $this->db->where('fsqid', $fsqid)->get('users')->row_array();
    }
    
    function insert($user) {
        return $this->db->insert('users', $user);
    }
    
    function update($fsqid, $user) {
        return $this->db->where('fsqid', $fsqid)->update('users', $user);
    }
    
    function exists($fsqid) {
        return $this->db->where('fsqid', $fsqid)->count_all_results('users') != 0;
    }
    
    function set_clan($fsqid, $clanid) {
        $this->db->where('fsqid', $fsqid)->update('users', array('clanid' => $clanid));
    }

}