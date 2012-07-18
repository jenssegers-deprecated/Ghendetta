<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class list_model extends CI_Model {
    
    function insert($list) {
        // default multiplier
        if (!isset($list['multiplier'])) {
            $list['multiplier'] = 2;
        }
        
        $this->db->insert('lists', $list);
        return $this->db->insert_id();
    }
    
    function set_multiplier($listid, $multiplier) {
        return $this->db->where('listid', $listid)->update('lists', array('multiplier' => $multiplier));
    }
    
    /**
     * Get all specials from a list
     * @param int $listid
     */
    function get_specials($listid) {
        $query = "
        	SELECT venues.*
            FROM specials
            JOIN venues ON venues.venueid = specials.venueid
            JOIN categories ON categories.categoryid = venues.categoryid
            WHERE listid = ?";
        
        return $this->db->query($query, array($listid))->result_array();
    }

}
