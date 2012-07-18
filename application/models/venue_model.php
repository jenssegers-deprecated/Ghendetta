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
        // prevent duplicate venues
        if($this->exists($venue['venueid'])) {
            return FALSE;
        }
        
        // detect checkin region
        $this->load->model('region_model');
        if(!$region = $this->region_model->detect_region($venue['lat'], $venue['lon'])) {
            return FALSE;
        }
        
        $venue['regionid'] = $region['regionid'];
        
        $this->db->insert('venues', $venue);
        return $venue['venueid'];
    }
    
    function update($venueid, $venue) {
        return $this->db->where('venueid', $venueid)->update('venues', $venue);
    }
    
    function get($venueid) {
        return $this->db->where('venueid', $venueid)->get('venues')->row_array();
    }
    
    function exists($venueid) {
        return $this->db->where('venueid', $venueid)->count_all_results('venues');
    }
    
    function count() {
        return $this->db->count_all('venues');
    }

}
