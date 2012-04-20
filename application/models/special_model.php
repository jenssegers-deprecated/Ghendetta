<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class special_model extends CI_Model {
    
    function insert($special) {
        $this->db->insert('specials', $special);
        return $this->db->insert_id();
    }
    
    function set_multiplier($listid, $venueid, $multiplier) {
        return $this->db->where('venueid', $venueid)->where('listid', $listid)->update('specials', array('multiplier' => $multiplier));
    }
    
    function get($listid, $venueid) {
        return $this->db->where('venueid', $venueid)->where('listid', $listid)->get('specials')->row_array();
    }
    
    /**
     * Get an active venue
     * @param int $venueid
     */
    function get_active($venueid) {
        $query = "
        	SELECT venues.*, lists.listid, lists.name as list, lists.startdate, lists.enddate, COALESCE(specials.multiplier , lists.multiplier) as multiplier
            FROM specials
            LEFT JOIN lists ON lists.listid = specials.listid
            JOIN venues ON venues.venueid = specials.venueid
            JOIN categories ON categories.categoryid = venues.categoryid
            WHERE specials.venueid = ? AND startdate <= UNIX_TIMESTAMP(NOW()) AND enddate >= UNIX_TIMESTAMP(NOW())
            ORDER BY multiplier DESC";
        
        return $this->db->query($query, array($venueid))->row_array();
    }
    
    /**
     * Get all active specials for all regions or a specific region
     */
    function get_all_active($regionid = FALSE) {
        $query = "
        	SELECT venues.*, lists.listid, lists.name as list, lists.startdate, lists.enddate, COALESCE(specials.multiplier , lists.multiplier) as multiplier
            FROM specials
            LEFT JOIN lists ON lists.listid = specials.listid
            JOIN venues ON venues.venueid = specials.venueid
            JOIN categories ON categories.categoryid = venues.categoryid
            WHERE startdate <= UNIX_TIMESTAMP(NOW()) AND enddate >= UNIX_TIMESTAMP(NOW())
            " . ($regionid ? "AND regionid = ?" : "");
        
        return $this->db->query($query, array($regionid))->result_array();
    }
    
    /**
     * Get the multiplier for a specific venue
     * @param int $venueid
     * @return foat
     */
    function get_multiplier($venueid) {
        // venue not found, return multiplier 1
        if (!$venue = $this->get_active($venueid)) {
            return 1;
        }
        
        return $venue['multiplier'];
    }
    
    /**
     * Generate a unique venue key
     * @param int $venueid
     * @param int $length
     * @return string
     */
    function generate_code($venueid, $length = 20) {
        $hash = hash('sha1', $venueid . $this->config->item('encryption_key'));
        $tot = strlen($hash);
        
        if ($length >= $tot) {
            return $hash;
        }
        
        return substr($hash, floor(($tot - $length) / 2), $length);
    }
    
    function count() {
        return $this->db->count_all('specials');
    }

}
