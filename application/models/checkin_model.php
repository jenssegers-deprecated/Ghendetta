<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class checkin_model extends CI_Model {
    
    function insert($checkin) {
        // prevent duplicated checkins
        if ($this->checkin_model->exists($checkin['checkinid'], $checkin['userid'], $checkin['venueid'], $checkin['date'])) {
            return FALSE;
        }
        
        // detect checkin region
        $this->load->model('region_model');
        $region = $this->region_model->detect_region($checkin['lat'], $checkin['lon']);
        
        // checkin must be inside a valid region
        if (!$region) {
            return FALSE;
        }
        
        // set regionid for the checkin
        $checkin['regionid'] = $region['regionid'];
        
        // get region clan (before checkin)
        $leader_before = $this->region_model->get_leader($checkin['regionid']);
        
        // detect a missing region transition
        if ($leader_before && $region['leader'] && $region['leader'] != $leader_before['clanid']) {
            $this->region_model->set_leader($region['regionid'], $leader_before['clanid'], $region['leader'], $leader_before['last_checkin']);
        }
        
        // get user information (before checkin)
        $this->load->model('user_model');
        $user = $this->user_model->get_stats($checkin['userid']);
        
        // get clan capo (before checkin)
        $this->load->model('clan_model');
        $capo = $this->clan_model->get_capo($user['clanid']);
        
        // default multiplier
        $multiplier = 1;
        
        // check for custom multiplier
        if (isset($checkin['multiplier'])) {
            $multiplier = $checkin['multiplier'];
            unset($checkin['multiplier']);
        }
        
        // calculate checkin points
        $checkin['points'] = $multiplier * $this->calculate_points($checkin['userid'], $checkin['date']);
        
        // insert checkin
        $this->db->insert('checkins', $checkin);
        $this->db->insert_id();
        
        // add new points to the user
        $user['points'] += $checkin['points'];
        
        // check new capo
        if (!$capo || ($user['fsqid'] != $capo['fsqid'] && $user['points'] > $capo['points'])) {
            // set new capo
            $this->clan_model->set_capo($user['clanid'], $user['fsqid']);
        }
        
        if (!$leader_before) {
            // there was no previous leader, this clan is taking this region
            $this->region_model->set_leader($checkin['regionid'], $user['clanid']);
        } else if ($user['clanid'] != $leader_before['clanid']) {
            // check for different region leader, but only if current user is in different clan!
            $leader_after = $this->region_model->get_leader($checkin['regionid']);
            
            if ($leader_after['clanid'] != $leader_before['clanid']) {
                // set new region leader
                $this->region_model->set_leader($checkin['regionid'], $leader_after['clanid'], $leader_before['clanid']);
            }
        }
        
        return $checkin['checkinid'];
    }
    
    /**
     * Check if a checkin exists base on checkinid,
     * prevents users to checkin into the same venue in small time periods
     * @param string $checkinid
     * @param int $userid
     * @param string $venueid
     * @param int $date
     * @return boolean
     */
    function exists($checkinid, $userid = FALSE, $venueid = FALSE, $date = FALSE) {
        if (func_num_args() == 1) {
            return $this->db->where('checkinid', $checkinid)->count_all_results('checkins');
        } else {
            if (!$date) {
                $date = time();
            }
            
            $query = "
        	SELECT COUNT(1) as count
        	FROM checkins
        	WHERE checkinid = ?
        		OR (venueid = ?
        			AND date >= ?
        			AND userid = ?)";
            
            $row = $this->db->query($query, array($checkinid, $venueid, $date, $userid))->row_array();
            return $row['count'] != 0;
        }
    }
    
    function get_all() {
        return $this->db->get('checkins')->result_array();
    }
    
    function get_since($userid, $since) {
        return $this->db->where('userid', $userid)->where('date >=', $since)->get('checkins')->result_array();
    }
    
    function get_unique_since($userid, $since) {
        return $this->db->where('userid', $userid)->where('date >=', $since)->group_by('venueid')->get('checkins')->result_array();
    }
    
    function get_last($userid) {
        return $this->db->where('userid', $userid)->order_by('date', 'desc')->limit(1)->get('checkins')->row_array();
    }
    
    function count($userid = FALSE) {
        if ($userid) {
            return $this->db->where('userid', $userid)->count_all_results('checkins');
        } else {
            return $this->db->count_all('checkins');
        }
    }
    
    function count_since($userid, $since = NULL) {
        // count_since(timestamp)
        if (is_null($since)) {
            $since = $userid;
            $userid = FALSE;
        }
        
        if ($userid) {
            return $this->db->where('userid', $userid)->where('date >=', $since)->count_all_results('checkins');
        } else {
            return $this->db->where('date >=', $since)->count_all_results('checkins');
        }
    }
    
    function count_between($userid, $start, $end = NULL) {
        // count_between(start, end)
        if (is_null($end)) {
            $end = $start;
            $start = $userid;
            $userid = FALSE;
        }
        
        if ($userid) {
            return $this->db->where('userid', $userid)->where('date >=', $start)->where('date <=', $end)->count_all_results('checkins');
        } else {
            return $this->db->where('date >=', $start)->where('date <=', $end)->count_all_results('checkins');
        }
    }
    
    /**
     * Get daily checkin count for a specific user
     * @param int $userid
     * @param int $days
     */
    function get_daily($userid, $days = 30) {
        $query = "
            SELECT FROM_UNIXTIME(date, GET_FORMAT(DATE,'EUR')) as date, COUNT(1) as battles
            FROM checkins
            WHERE userid = ? AND date >= UNIX_TIMESTAMP(SUBDATE(NOW(),?))
            GROUP BY FROM_UNIXTIME(date, GET_FORMAT(DATE,'EUR'))
            ORDER BY date DESC";
        
        return $this->db->query($query, array($userid, $days))->result_array();
    }
    
    /**
     * Get hourly checkin count for a specific user
     * @param int $userid
     * @param int $days
     */
    function get_hourly($userid, $days = 1) {
        $query = "
            SELECT FROM_UNIXTIME(date, '%Y-%m-%d %H') as date, FROM_UNIXTIME(date, '%H') as hour, COUNT(1) as battles
            FROM checkins
            WHERE userid = ? AND date >= UNIX_TIMESTAMP(SUBDATE(NOW(),?))
            GROUP BY FROM_UNIXTIME(date, '%Y-%m-%d %H')
            ORDER BY date DESC";
        
        return $this->db->query($query, array($userid, $days))->result_array();
    }
    
    /**
     * Points algorithm, calculate next checkin points based on history
     * @param int $userid
     * @param int $time
     */
    function calculate_points($userid, $time) {
        /* 
         * Short term: 15 minutes
         * Mid term: 1 hour
         * Long term: 24 hours
         */
        
        $query = "
        	SELECT 
                COUNT(CASE
                    WHEN date >= ? THEN 1
                    ELSE null
                END) as 'short',
                COUNT(CASE
                    WHEN date >= ? THEN 1
                    ELSE null
                END) as 'mid',
                count(1) as 'long'
            FROM checkins
            WHERE userid = ? AND date >= ? AND date <= ?";
        
        $count = $this->db->query($query, array($time - 900, $time - 3600, $userid, $time - 86400, $time))->row_array();
        
        $short_term = $count['short'] + 1;
        $mid_term = $count['mid'] + 1;
        $long_term = $count['long'] + 1;
        
        $ratio = 1;
        
        if ($short_term > 3) {
            $ratio *= pow(0.90, $short_term - 3);
        }
        
        if ($mid_term > 6) {
            $ratio *= pow(0.95, $mid_term - 6);
        }
        
        if ($long_term > 30) {
            $ratio *= pow(0.90, $long_term - 30);
        }
        
        return $ratio;
    }

}
