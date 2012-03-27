<?php

class clan_model extends CI_Model {
    
    function insert($clan) {
        $this->db->insert('clans', $clan);
        return $this->db->insert_id();
    }
    
    function update($clanid, $clan) {
        return $this->db->where('clanid', $clanid)->update('clans', $clan);
    }
    
    function get($clanid) {
    	return $this->db->where('clanid', $clanid)->get('clans')->row_array();
    }
    
    function get_all() {
        return $this->db->get('clans')->result_array();
    }

    function get_members( $clanid ){
        return $this->db->query( sprintf('select fsqid, firstname, lastname, picurl, count(1) as checkins
                                          from users u 
                                          left outer join checkins c on u.fsqid = c.userid
					  where clanid = %d 
					  group by fsqid, firstname, lastname, picurl
					  order by checkins desc',$clanid
                                         ))->result_array();
    }
    
    function suggest() {
        $results = $this->db->query('
        SELECT clans.*, count(1) as count
        FROM clans
        LEFT JOIN users ON users.clanid = clans.clanid
        LEFT JOIN checkins ON checkins.userid = users.fsqid AND checkins.date >= UNIX_TIMESTAMP( subdate(now(),7) )
        GROUP BY clans.clanid
        ORDER BY count ASC
	LIMIT 0, 1
        ')->result_array();
        
        // return the first clan with the lowest number of checkins
        return reset($results);
    }
    /*
	
SELECT fsqid, firstname, lastname, groupid, picurl, count(1) as count FROM users u cross join checkins c on u.fsqid = c.userid 
where clanid = 2 and c.date >= UNIX_TIMESTAMP( subdate(now(),7) )group by u.fsqid, c.userid, firstname, lastname, groupid, picurl	
*/
}

?>
