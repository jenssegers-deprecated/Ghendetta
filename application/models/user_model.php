<?php

class user_model extends CI_Model {
    
    function get($fsqid) {
        return $this->db->where('fsqid', $fsqid)->get('users')->row_array();
    }
    
    function insert($user) {
        $user['registered'] = time();
        
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
    
    /**
     * Get a specific user, with total points and ranking in clan
     * @param int $fsqid
     */
    function get_stats($fsqid) {
        $user = $this->get($fsqid);
        
        $query = '
        	SELECT * 
    		FROM (
    		  	SELECT fsqid, firstname, lastname, picurl, clanid, points, @rownum:=@rownum+1 as rank 
              	FROM (
              		SELECT fsqid, firstname, lastname, picurl, clanid, count(checkins.checkinid) as points
            		FROM users 
            		LEFT JOIN checkins ON users.fsqid = checkins.userid AND checkins.date >= UNIX_TIMESTAMP( subdate(now(),7) )
            		WHERE users.clanid = ?
            		GROUP BY users.fsqid
            		ORDER BY points desc, CASE fsqid WHEN ? THEN 1 ELSE 0 END
            		) t, (SELECT @rownum:=0) r
            	) t
            WHERE fsqid = ?';
        
        return $this->db->query($query, array($user['clanid'], $fsqid, $fsqid))->row_array();
    }
    
    /**
     * Calculate the points of a user
     * @param int $userid
     */
    function get_points($userid) {
        $query = '
        	SELECT count(checkinid) as points
        	FROM checkins
        	WHERE date >= UNIX_TIMESTAMP( subdate(now(),7) )
        	AND userid = ?';
        
        $row = $this->db->query($query, array($userid))->row_array();
        return $row['points'];
    }

}