<?php

class request_model extends CI_Model {
    
    function get_all() {
        return $this->db->get('requests')->result_array();
    }
    
    function insert($request) {
        $this->db->insert('requests', $request);
        return $this->db->insert_id();
    }
    
    function truncate() {
        $this->db->truncate('requests');
    }
    
    function count() {
        return $this->db->count_all('requests');
    }
    
    /**
     * Get the request count of the last 30 days
     */
    function get_daily() {
        $query = "
        	SELECT FROM_UNIXTIME(time, GET_FORMAT(DATE,'EUR')) as date, time as timestamp, count(1) as requests
        	FROM requests
        	WHERE time >= UNIX_TIMESTAMP(subdate(now(),30))
        	GROUP BY FROM_UNIXTIME(time, GET_FORMAT(DATE,'EUR'))";
        
        return $this->db->query($query)->result_array();
    }
    
    /**
     * Clean all requests older then $since seconds (default is 50 days)
     * @param int $since
     */
    function clean($since = FALSE) {
        if (!$since) {
            $since = time() - 4320000; // 50 days
        }
        
        $this->db->where('time <=', $since)->delete('requests');
    }

}