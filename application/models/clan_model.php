<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

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
    
    function count() {
        return $this->db->count_all('clans');
    }
    
    function count_members($clanid) {
        return $this->db->where(array('clanid' => $clanid, 'active' => 1))->count_all_results('users');
    }
    
    /**
     * Get specific clan, with total member points
     * @param int $clanid
     */
    function get_stats($clanid) {
        $query = "
            SELECT cl.*, u.fsqid, COUNT(u.active) AS members,
                (SELECT COUNT(c.checkinid)
                FROM users u2
                JOIN checkins c ON c.userid = u2.fsqid
                WHERE u2.clanid = u.clanid AND c.date >= UNIX_TIMESTAMP(SUBDATE(NOW(),7))) AS battles,
                (SELECT FLOOR(SUM(c.points)) FROM users u2
                JOIN checkins c ON c.userid = u2.fsqid
                WHERE u2.clanid = u.clanid AND c.date >= UNIX_TIMESTAMP(SUBDATE(NOW(),7))) AS points
            FROM clans cl
            JOIN users u ON cl.clanid = u.clanid AND cl.clanid = ?";
        
        return $this->db->query($query, array($clanid))->row_array();
    }
    
    /**
     * Get all clans, with total member points
     */
    function get_all_stats() {
        $query = "
            SELECT cl.*, u.fsqid, COUNT(u.active) AS members,
                (SELECT COUNT(c.checkinid)
                FROM users u2
                JOIN checkins c ON c.userid = u2.fsqid
                WHERE u2.clanid = u.clanid AND c.date >= UNIX_TIMESTAMP(SUBDATE(NOW(),7))) AS battles,
                (SELECT FLOOR(SUM(c.points)) FROM users u2
                JOIN checkins c ON c.userid = u2.fsqid
                WHERE u2.clanid = u.clanid AND c.date >= UNIX_TIMESTAMP(SUBDATE(NOW(),7))) AS points
            FROM clans cl
            JOIN users u ON cl.clanid = u.clanid
            GROUP BY cl.clanid";
        
        return $this->db->query($query)->result_array();
    }
    
    /**
     * Get all members of a specific clan
     * If the third $fsqid is used and that person does not have any checkins, this user will be placed last
     * @param int $clanid
     * @param int $limit
     * @param int $fsqid
     */
    function get_members($clanid, $limit = FALSE, $fsqid = FALSE) {
        $query = "
            SELECT t.*, @rownum:=@rownum+1 as rank
            FROM (
                SELECT fsqid, firstname, lastname, picurl, COALESCE(points, 0) as points, COALESCE(battles, 0) as battles
                FROM users
                LEFT JOIN (
                	SELECT fsqid as userid, FLOOR(SUM(checkins.points)) as points, COUNT(checkins.checkinid) as battles
                	FROM users
                	JOIN checkins ON users.fsqid = checkins.userid AND checkins.date >= UNIX_TIMESTAMP(SUBDATE(now(),7))
                	WHERE users.clanid = ? AND users.active = 1
                	GROUP BY users.fsqid
                ) as sub ON sub.userid = users.fsqid
                WHERE users.clanid = ? AND users.active = 1
                ORDER BY points DESC " . ($fsqid ? ", CASE fsqid WHEN ? THEN 1 ELSE 0 END " : "") . ($limit ? "LIMIT 0,?" : "") . "
            ) t, (SELECT @rownum:=0) r";

        return $this->db->query($query, array($clanid, $clanid, $fsqid, $limit))->result_array();
    }
    
    /**
     * Get the capo of a clan
     * @param int $clanid
     */
    function get_capo($clanid) {
        $query = "
        	SELECT fsqid, firstname, lastname, picurl, COALESCE(FLOOR(SUM(checkins.points)), 0) as points, COUNT(checkins.checkinid) as battles
            FROM users
            JOIN checkins ON users.fsqid = checkins.userid AND checkins.date >= UNIX_TIMESTAMP(SUBDATE(now(),7))
            WHERE users.clanid = ? AND users.active = 1
            GROUP BY users.fsqid
            ORDER BY points DESC, checkins.date DESC
            LIMIT 0,1";
        
        return $this->db->query($query, array($clanid))->row_array();
    }
    
    /**
     * Suggest clan based on total checkins in the last 7 days between all clans
     */
    function suggest_clan() {
        $query = "
            SELECT clans.*, COALESCE(FLOOR(SUM(checkins.points)), 0) as points
            FROM clans
            LEFT JOIN users ON users.clanid = clans.clanid AND users.active = 1
            LEFT JOIN checkins ON checkins.userid = users.fsqid AND checkins.date >= UNIX_TIMESTAMP(SUBDATE(now(),7))
            GROUP BY clans.clanid
            ORDER BY points ASC
            LIMIT 0, 1";
        
        return $this->db->query($query)->row_array();
    }

}
