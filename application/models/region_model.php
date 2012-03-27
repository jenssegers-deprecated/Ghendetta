<?php

class region_model extends CI_Model {
    
    function insert_region($region) {
        $this->db->insert('regions', $region);
        return $this->db->insert_id();
    }
    
    function insert_coords($coords) {
        $this->db->insert('regioncoords', $coords);
        return $this->db->insert_id();
    }
    
    function get_all() {
        $regions = $this->db->get('regions')->result_array();
        foreach ($regions as &$region) {
            $region['coords'] = $this->db->select('lon, lat')->where('regionid', $region['regionid'])->get('regioncoords')->result_array();
        }
        
        return $regions;
    }
    
    function truncate() {
        $this->db->truncate("regions");
        $this->db->truncate("regioncoords");
    }
    
    function battlefield() {
        $this->load->model('clan_model');
        
        $results = $this->db->query('
        SELECT regions.regionid, clans.*, count(1) as points 
        FROM regions
        JOIN checkins ON checkins.regionid = regions.regionid AND checkins.date >= ' . (time() - 604800) . ' 
        JOIN users ON users.fsqid = checkins.userid
        JOIN clans ON clans.clanid = users.clanid
        GROUP BY checkins.regionid, users.clanid
        ORDER BY regions.regionid ASC, points DESC
        ')->result_array();
        
        // select the leading clan for each region
        $leaderboard = array();
        foreach ($results as $result) {
            $rid = $result['regionid'];
            
            if (!isset($leaderboard[$rid]) || $leaderboard[$rid]['points'] < $result['points']) {
                $leaderboard[$rid] = $result;
            }
        }
        
        // add the leading clan to the region data
        $regions = $this->get_all();
        foreach ($regions as &$region) {
            $rid = $region['regionid'];
            
            if (isset($leaderboard[$rid])) {
                $region['clan'] = $leaderboard[$rid];
            } else {
                $region['clan'] = FALSE;
            }
        }
        
        return $regions;
    }

}
