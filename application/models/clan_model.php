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
    
    function get_members($clanid, $limit = FALSE) {
        return $this->db->query('
            SELECT fsqid, firstname, lastname, picurl, count(checkins.checkinid) as points
            FROM users 
            LEFT JOIN checkins ON users.fsqid = checkins.userid AND checkins.date >= UNIX_TIMESTAMP( subdate(now(),7) )
            WHERE users.clanid = ?
            GROUP BY users.fsqid
            ORDER BY points DESC
            ' . ($limit ? 'LIMIT 0,?' : ''), array($clanid, $limit))->result_array();
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
    
    function count() {
        return $this->db->count_all('clans');
    }
    
    function count_members($clanid) {
        return $this->db->where('clanid', $clanid)->count_all_results('users');
    }

}

?>
