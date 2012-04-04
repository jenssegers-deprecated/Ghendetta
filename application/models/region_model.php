<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class region_model extends CI_Model {
    
    function insert_region($region) {
        // remove cached regions
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'dummy'));
        $this->cache->delete("model/regions.cache");
        
        $this->db->insert('regions', $region);
        return $this->db->insert_id();
    }
    
    function insert_coords($coords) {
        // remove cached regions
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'dummy'));
        $this->cache->delete("model/regions.cache");
        
        $this->db->insert('regioncoords', $coords);
        return $this->db->insert_id();
    }
    
    function update($regionid, $region) {
        // remove cached regions
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'dummy'));
        $this->cache->delete("model/regions.cache");
        
        return $this->db->where('regionid', $regionid)->update('regions', $region);
    }
    
    /**
     * Get one region without manipulation
     * @param int $regionid
     */
    function get($regionid) {
        // add cache to this method, regions will not change that often
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'dummy'));
        
        if (!$region = $this->cache->get("model/region-$regionid.cache")) {
            $region = $this->db->where('regionid', $regionid)->get('regions')->result_array();
            $region['coords'] = $this->get_coords($regionid);
            
            $this->cache->save("model/region-$regionid.cache", $region, 7200);
        }
        
        return $region;
    }
    
    /**
     * Get the coordinates of a region
     * @param int $regionid
     */
    function get_coords($regionid) {
        return $this->db->select('lon, lat')->where('regionid', $regionid)->get('regioncoords')->result_array();
    }
    
    /**
     * Get all regions without manipulation
     */
    function get_all() {
        // add cache to this method, regions will not change that often
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'dummy'));
        
        if (!$regions = $this->cache->get("model/regions.cache")) {
            $regions = $this->db->get('regions')->result_array();
            
            foreach ($regions as &$region) {
                $region['coords'] = $this->get_coords($region['regionid']);
            }
            
            $this->cache->save("model/regions.cache", $regions, 7200);
        }
        
        return $regions;
    }
    
    /**
     * Calculate the leading clan of a specific region
     * @param int $regionid
     * @param bool $redundant
     */
    function get_leader($regionid) {
        $query = '
            SELECT regions.regionid, clans.*, COALESCE(FLOOR(SUM(checkins.points)), 0) as points, COUNT(checkins.checkinid) as battles
            FROM regions
            LEFT JOIN checkins ON checkins.regionid = regions.regionid AND checkins.date >= UNIX_TIMESTAMP(SUBDATE(now(),7)) 
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
    function get_all_stats() {
        $query = '
        	SELECT * 
        	FROM (
                SELECT regions.regionid, clans.*, COALESCE(FLOOR(SUM(checkins.points)), 0) as points, COUNT(checkins.checkinid) as battles
                FROM regions
                LEFT JOIN checkins ON checkins.regionid = regions.regionid AND checkins.date >= UNIX_TIMESTAMP(SUBDATE(now(),7)) 
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
        $regions = $this->region_model->get_all();
        foreach ($regions as &$region) {
            $rid = $region['regionid'];
            
            if (isset($leaders[$rid])) {
                $region['leader'] = $leaders[$rid];
            } else {
                $region['leader'] = FALSE;
            }
        }
        
        return $regions;
    }
    
    /**
     * Get the clan standings for a specific region
     * @param int $regionid
     */
    function get_stats($regionid) {
        $query = '
            SELECT clans.*, COALESCE(FLOOR(SUM(checkins.points)), 0) as points, COUNT(checkins.checkinid) as battles
            FROM regions
            LEFT JOIN checkins ON checkins.regionid = regions.regionid AND checkins.date >= UNIX_TIMESTAMP(SUBDATE(now(),7)) 
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
