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
    
    function get_members($clanid, $limit = FALSE, $fsqid = FALSE ) {
	// added parameter $fsqid for the following reason:
	// if you haven't checked in, you should be LAST
	// in case of ex aequo: last of those
        return $this->db->query('
            SELECT fsqid, firstname, lastname, picurl, count(checkins.checkinid) as points
            FROM users 
            LEFT JOIN checkins ON users.fsqid = checkins.userid AND checkins.date >= UNIX_TIMESTAMP( subdate(now(),7) )
            WHERE users.clanid = ?
            GROUP BY users.fsqid
            ORDER BY points DESC '. ( $fsqid ? ', CASE fsqid WHEN ? then 1 else 0 end ' : '' ) .
            ($limit ? 'LIMIT 0,?' : ''), array($clanid, $fsqid, $limit ))->result_array();
    }
    
    function suggest_clan() {
        return $this->db->query('
            SELECT clans.*, count(checkins.checkinid) as points
            FROM clans
            LEFT JOIN users ON users.clanid = clans.clanid
            LEFT JOIN checkins ON checkins.userid = users.fsqid AND checkins.date >= UNIX_TIMESTAMP( subdate(now(),7) )
            GROUP BY clans.clanid
            ORDER BY points ASC
            LIMIT 0, 1
            ')->row_array();
    }

    function get_internal_rank( $clanid, $fsqid ){
	// gives top 3, their fsqid, firstname, lastname, picurl, points
	// also: user with fsqid's attributes and ranking
	$result = array();
	$result['top3'] = $this->get_members($clanid, 3, $fsqid) ;
	$query = 'SELECT * FROM (SELECT fsqid, firstname, lastname, picurl, points, @rownum:=@rownum+1 as rank 
                  FROM (SELECT fsqid, firstname, lastname, picurl, count(checkins.checkinid) as points
            		FROM users 
            		LEFT JOIN checkins ON users.fsqid = checkins.userid AND checkins.date >= UNIX_TIMESTAMP( subdate(now(),7) )
            	  	WHERE users.clanid = ?
            	  	GROUP BY users.fsqid
            		ORDER BY points desc, CASE fsqid WHEN ? THEN 1 ELSE 0 END 
		  ) t,  (SELECT @rownum:=0) r ) t WHERE fsqid = ?';
	$result['user'] = $this->db->query($query,array($clanid,3,$fsqid,$fsqid))->result_array();
	return $result;
    }

}

?>
