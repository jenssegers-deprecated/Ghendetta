<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class request_model extends CI_Model {
    
    function get_all() {
        return $this->db->order_by('time', 'DESC')->get('requests')->result_array();
    }
    
    function insert($request) {
        $this->db->insert('requests', $request);
        return $this->db->insert_id();
    }
    
    function truncate() {
        $this->db->truncate('requests');
    }
    
    function count($type = 'all') {
        switch (strtolower($type)) {
            case 'regular' :
                return $this->db->where("NOT SUBSTRING(uri, 1, 4) = 'api/'", NULL, FALSE)->count_all_results('requests');
            case 'api' :
                return $this->db->where("SUBSTRING(uri, 1, 4) = 'api/'", NULL, FALSE)->count_all_results('requests');
            case 'push' :
                return $this->db->where("SUBSTRING(uri, 1, 15) = 'foursquare/push'", NULL, FALSE)->count_all_results('requests');
            case 'cronjob' :
                return $this->db->where("SUBSTRING(uri, 1, 18) = 'foursquare/cronjob'", NULL, FALSE)->count_all_results('requests');
            default :
                return $this->db->count_all('requests');
        }
    }
    
    /**
     * Get the request count of the last 30 days
     */
    function get_daily($type = 'all') {
        
        $where = '';
        switch (strtolower($type)) {
            case 'regular' :
                $where = "AND NOT SUBSTRING(uri, 1, 4) = 'api/'";
                break;
            case 'api' :
                $where = "AND SUBSTRING(uri, 1, 4) = 'api/'";
                break;
            case 'push' :
                $where = "AND SUBSTRING(uri, 1, 15) = 'foursquare/push'";
                break;
            case 'cronjob' :
                $where = "AND SUBSTRING(uri, 1, 18) = 'foursquare/push'";
                break;
        }
        
        $query = "
        	SELECT FROM_UNIXTIME(time, GET_FORMAT(DATE,'EUR')) as date, count(1) as requests
        	FROM requests
        	WHERE time >= UNIX_TIMESTAMP(subdate(now(),30)) " . $where . "
        	GROUP BY FROM_UNIXTIME(time, GET_FORMAT(DATE,'EUR'))
        	ORDER BY time DESC";
        
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