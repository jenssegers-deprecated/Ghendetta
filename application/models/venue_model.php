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
    
    function update($venueid, $venue) {
        return $this->db->where('venueid', $venueid)->update('venues', $venue);
    }
    
    function get($venueid) {
        return $this->db->where('venueid', $venueid)->get('venues')->row_array();
    }
    
    function get_active($venueid) {
        $query = "
        	SELECT venues.*, categories.name as category, categories.icon as icon
        	FROM venues
        	JOIN categories ON categories.categoryid = venues.categoryid
        	WHERE venueid = ? AND startdate <= UNIX_TIMESTAMP(NOW()) AND enddate >= UNIX_TIMESTAMP(NOW())";
        
        return $this->db->query($query, array($venueid))->row_array();
    }
    
    function get_all_active() {
        $query = "
        	SELECT venues.*, categories.name as category, categories.icon as icon
        	FROM venues
        	JOIN categories ON categories.categoryid = venues.categoryid
        	WHERE startdate <= UNIX_TIMESTAMP(NOW()) AND enddate >= UNIX_TIMESTAMP(NOW())";
        
        return $this->db->query($query)->result_array();
    }
    
    function count() {
        return $this->db->count_all('venues');
    }

}
