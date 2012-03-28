<?php

class request_model extends CI_Model {
    
    function insert($request) {
        $this->db->insert('requests', $request);
        return $this->db->insert_id();
    }
    
    function truncate() {
        $this->db->truncate('requests');
    }
    
    function clean($since = FALSE) {
        if (!$since) {
            // 50 days
            $since = time() - 4320000;
        }
        
        $this->db->where('time <=', $since)->delete('requests');
    }
    
    function count() {
        return $this->db->count_all('requests');
    }

}