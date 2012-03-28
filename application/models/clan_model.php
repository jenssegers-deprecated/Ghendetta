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
    
    function get_members($clanid, $limit = FALSE, $fsqid = FALSE) {
        // added parameter $fsqid for the following reason:
        // if you haven't checked in, you should be LAST
        // in case of ex aequo: last of those
        $query = '
    		SELECT fsqid, firstname, lastname, picurl, count(checkins.checkinid) as points
            FROM users 
            LEFT JOIN checkins ON users.fsqid = checkins.userid AND checkins.date >= UNIX_TIMESTAMP( subdate(now(),7) )
            WHERE users.clanid = ?
            GROUP BY users.fsqid
            ORDER BY points DESC ' . ($fsqid ? ', CASE fsqid WHEN ? THEN 1 ELSE 0 END ' : '') . ($limit ? 'LIMIT 0,?' : '');
        
        return $this->db->query($query, array($clanid, $fsqid, $limit))->result_array();
    }
    
    function suggest_clan() {
        $query = '
            SELECT clans.*, count(checkins.checkinid) as points
            FROM clans
            LEFT JOIN users ON users.clanid = clans.clanid
            LEFT JOIN checkins ON checkins.userid = users.fsqid AND checkins.date >= UNIX_TIMESTAMP( subdate(now(),7) )
            GROUP BY clans.clanid
            ORDER BY points ASC
            LIMIT 0, 1';
        
        return $this->db->query($query)->row_array();
    }
    
    function count() {
        return $this->db->count_all('clans');
    }
    
    function count_members($clanid) {
        return $this->db->where('clanid', $clanid)->count_all_results('users');
    }

}

?>
