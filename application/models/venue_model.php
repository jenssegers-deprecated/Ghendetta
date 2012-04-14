<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class venue_model extends CI_Model {
    
    function insert($venue) {
        $this->db->insert('venues', $venue);
        return $this->db->insert_id();
    }
    
    function insert_list($list) {
        if (!isset($list['multiplier'])) {
            $list['multiplier'] = 2;
        }
        
        $this->db->insert('venuelists', $list);
        return $this->db->insert_id();
    }
    
    function update($venueid, $venue) {
        return $this->db->where('venueid', $venueid)->update('venues', $venue);
    }
    
    function get($venueid) {
        return $this->db->where('venueid', $venueid)->get('venues')->row_array();
    }
    /**
     * used to check in with a url containing this hash
     * 
     */
    function get_by_hash( $hash ){
        $query =   "SELECT v.listid, v.venueid, v.name, v.lon, v.lat, v.regionid, 
                    coalesce( v.multiplier, l.multiplier), c.icon, c.name as category, l.name as listname FROM venues v
                    JOIN venuelists l on l.listid = v.listid
                    JOIN categories c on c.categoryid = v.categoryid
                    WHERE hash = ? AND startdate <= UNIX_TIMESTAMP(NOW()) AND enddate >= UNIX_TIMESTAMP(NOW())";
        return $this->db->query($query, array($hash))->row_array();
    }
    
    /**
     * Get an active venue
     * @param int $venueid
     */
    function get_active($venueid) {
        $query = "
        	SELECT l.name AS listname, startdate, enddate, 
                   coalesce( v.multiplier , l.multiplier ) as multiplier, v.listid, v.venueid,
                   v.name, v.categoryid, v.lon, v.lat, v.regionid, c.name as category, c.icon
            FROM venuelists l
            JOIN venues v ON v.listid = l.listid
            JOIN categories c ON v.categoryid = c.categoryid
        	WHERE venueid = ? AND startdate <= UNIX_TIMESTAMP(NOW()) AND enddate >= UNIX_TIMESTAMP(NOW())";
        
        return $this->db->query($query, array($venueid))->row_array();
    }
    
    /**
     * Get all active venues for all regions or a specific region
     */
    function get_all_active($regionid = FALSE) {
        $query = "
        	SELECT l.name AS listname, startdate, enddate, 
                   coalesce( v.multiplier , l.multiplier ) as multiplier, v.listid, v.venueid,
                   v.name, v.categoryid, v.lon, v.lat, v.regionid, c.name as category, c.icon
            FROM venuelists l
            JOIN venues v ON v.listid = l.listid
            JOIN categories c ON v.categoryid = c.categoryid" . ($regionid ? ' AND v.regionid = ? ' : '') . "
        	WHERE startdate <= UNIX_TIMESTAMP(NOW()) AND enddate >= UNIX_TIMESTAMP(NOW())";
        return $this->db->query($query, array($regionid))->result_array();
    }
    
    /**
     * Get the multiplier for a specific venue with a to be validated message
     * @param int $venueid
     * @param string $message
     * @return foat
     */
    function get_multiplier($venueid, $message = FALSE) {
        // venue not found, return multiplier 1
        if (!$venue = $this->get_active($venueid)) {
            return 1;
        }
        
        if ($venue['validator']) {
            // validator was supplied and matches
            if ($venue['multiplier'] && stristr($venue['multiplier'], $venue['validator'])) {
                return $venue['multiplier'];
            }
            
            // return 1 in all other cases
            return 1;
        } else {
            return $venue['validator'];
        }
    }
    
    function count() {
        return $this->db->count_all('venues');
    }

}
