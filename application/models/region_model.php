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
    
    function update($regionid, $region) {
        return $this->db->where('regionid', $regionid)->update('regions', $region);
    }
    
    /**
     * Get all regions without manipulation
     */
    function get_all() {
        $regions = $this->db->get('regions')->result_array();
        
        foreach ($regions as &$region) {
            $region['coords'] = $this->db->select('lon, lat')->where('regionid', $region['regionid'])->get('regioncoords')->result_array();
        }
        
        return $regions;
    }
    
    /**
     * Calculate the leading clan of a specific region
     * @param int $regionid
     */
    function region_leader($regionid) {
        $query = '
            SELECT regions.regionid, clans.*, count(checkinid) as points
            FROM regions
            LEFT JOIN checkins ON checkins.regionid = regions.regionid AND checkins.date >= UNIX_TIMESTAMP( subdate(now(),7) ) 
            LEFT JOIN users ON users.fsqid = checkins.userid
            LEFT JOIN clans ON clans.clanid = users.clanid
            WHERE regions.regionid = ?
            GROUP BY users.clanid
            ORDER BY regions.regionid ASC, points DESC
            LIMIT 0,1';
        
        return $this->db->query($query, array($regionid))->row_array();
    }
    
    /**
     * Get all regions with corresponding leading clan
     */
    function all_region_stats() {
        $query = '
        	SELECT * 
        	FROM (
                SELECT regions.regionid, clans.*, count(checkinid) as points
                FROM regions
                LEFT JOIN checkins ON checkins.regionid = regions.regionid AND checkins.date >= UNIX_TIMESTAMP( subdate(now(),7) ) 
                LEFT JOIN users ON users.fsqid = checkins.userid
                LEFT JOIN clans ON clans.clanid = users.clanid
                GROUP BY checkins.regionid, users.clanid
                ORDER BY regions.regionid ASC, points DESC ) sub
            GROUP BY regionid';
        
        $results = $this->db->query($query)->result_array();
        
        // make array with region id as key
        $leaders = array();
        foreach ($results as $result) {
            $leaders[$result['regionid']] = $result;
        }
        
        // add the leading clan to the region data
        $regions = $this->get_all();
        foreach ($regions as &$region) {
            $rid = $region['regionid'];
            
            if (isset($leaders[$rid])) {
                $region['clan'] = $leaders[$rid];
            } else {
                $region['clan'] = FALSE;
            }
        }
        
        return $regions;
    }
    
    /**
     * Get the clan standings for a specific region
     * @param int $regionid
     */
    function region_stats($regionid) {
        $query = '
            SELECT regions.regionid, clans.*, count(checkinid) as points
            FROM regions
            LEFT JOIN checkins ON checkins.regionid = regions.regionid AND checkins.date >= UNIX_TIMESTAMP( subdate(now(),7) ) 
            LEFT JOIN users ON users.fsqid = checkins.userid
            LEFT JOIN clans ON clans.clanid = users.clanid
            WHERE regions.regionid = ?
            GROUP BY checkins.regionid, users.clanid
            ORDER BY regions.regionid ASC, points DESC';
        
        return $this->db->query($query, array($regionid))->result_array();
    }
    
    function count() {
        return $this->db->count_all('regions');
    }
    
    /**
     * Remove all region data
     */
    function truncate() {
        $this->db->truncate('regions');
        $this->db->truncate('regioncoords');
    }

}
