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

    private $regions = FALSE ;
    
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
            $region = $this->db->where('regionid', $regionid)->get('regions')->row_array();
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

    function detect_region( $lat, $long ){
        
        if (is_null($this->regions)) {
            $this->regions = $this->get_all();
        }

        $found_region = FALSE;
        foreach ( $this->regions as $region ) {
            if (is_in_polygon($region['coords'], $lon, $lat)) {
                $found_region = $region;
                break; // yes this is a break :)
            }
        }
        
        return $found_region ;
    }
    
    /**
     * Calculate the leading clan of a specific region
     * @param int $regionid
     */
    function get_leader($regionid) {
        $query = "
            SELECT regions.regionid, clans.*, MAX(checkins.date) as last_checkin, COALESCE(FLOOR(SUM(checkins.points)), 0) as points, COUNT(checkins.checkinid) as battles
            FROM regions
            JOIN checkins ON checkins.regionid = regions.regionid AND checkins.date >= UNIX_TIMESTAMP(SUBDATE(now(),7)) 
            JOIN users ON users.fsqid = checkins.userid
            JOIN clans ON clans.clanid = users.clanid
            WHERE regions.regionid = ?
            GROUP BY users.clanid
            ORDER BY regions.regionid ASC, points DESC, last_checkin ASC
            LIMIT 0,1";
        
        return $this->db->query($query, array($regionid))->row_array();
    }
    
    /**
     * Set the new leader for a specific region
     * @param int $regionid
     * @param int $new
     * @param int $old
     */
    function set_leader($regionid, $new, $old = FALSE) {
        // set leader
        $this->update($regionid, array('leader' => $new));
        
        // get region
        $region = $this->get($regionid);
        
        // get new clan
        $this->load->model('clan_model');
        $new_clan = $this->clan_model->get($new);
        
        $this->load->model('notification_model');
        
        // insert region_lost notification
        if ($old) {
            // get old clan
            $old_clan = $this->clan_model->get($old);
            
            $notification = array();
            $notification['type'] = 'region_lost';
            $notification['to'] = $old_clan['clanid'];
            $notification['to_type'] = 'clan';
            $notification['data'] = array('region' => $region['name'], 'clanid' => $new_clan['clanid'], 'clan' => $new_clan['name'], 'color' => $new_clan['color']);
            $this->notification_model->insert($notification);
        }
        
        // insert region_won notification
        $notification = array();
        $notification['type'] = 'region_won';
        $notification['to'] = $new_clan['clanid'];
        $notification['to_type'] = 'clan';
        $notification['data'] = array('region' => $region['name'], 'clanid' => $new_clan['clanid'], 'clan' => $new_clan['name'], 'color' => $new_clan['color']);
        $this->notification_model->insert($notification);
    }
    
    /**
     * Get all regions with corresponding leading clan
     */
    function get_all_stats() {
        $query = "
            SELECT regions.regionid, clans.*, COALESCE(FLOOR(SUM(checkins.points)), 0) as points, COUNT(checkins.checkinid) as battles
            FROM regions
            LEFT JOIN checkins ON checkins.regionid = regions.regionid AND checkins.date >= UNIX_TIMESTAMP(SUBDATE(now(),7)) 
            LEFT JOIN users ON users.fsqid = checkins.userid
            LEFT JOIN clans ON clans.clanid = users.clanid
            GROUP BY checkins.regionid, clans.clanid
            ORDER BY regions.regionid ASC, clans.clanid ASC";
        
        $results = $this->db->query($query)->result_array();
        
        // make array with region id as key
        $clans = array();
        foreach ($results as $result) {
            $rid = $result['regionid'];
            unset($result['regionid']);
            
            $clans[$rid][$result['clanid']] = $result;
        }
        
        // add the clans to the region data
        $regions = $this->region_model->get_all();
        foreach ($regions as &$region) {
            $rid = $region['regionid'];
            
            if (isset($clans[$rid])) {
                $region['clans'] = $clans[$rid];
                
                // TODO: remove this part when the database bug has been resolved!
                $max = 0;
                foreach ($clans[$rid] as $clan) {
                    if ($clan['points'] > $max) {
                        $max = $clan['points'];
                        $region['leader'] = $clan['clanid'];
                    }
                }
            
            } else {
                $region['clans'] = array();
            }
        }
        
        return $regions;
    }
    
    /**
     * Get the clan standings for a specific region
     * @param int $regionid
     */
    function get_stats($regionid) {
        $query = "
            SELECT clans.*, COALESCE(FLOOR(SUM(checkins.points)), 0) as points, COUNT(checkins.checkinid) as battles
            FROM regions
            JOIN checkins ON checkins.regionid = regions.regionid AND checkins.date >= UNIX_TIMESTAMP(SUBDATE(now(),7)) 
            JOIN users ON users.fsqid = checkins.userid
            JOIN clans ON clans.clanid = users.clanid
            WHERE regions.regionid = ?
            GROUP BY checkins.regionid, clans.clanid
            ORDER BY regions.regionid ASC, clans.clanid ASC";
        
        $region = $this->get($regionid);
        $region['clans'] = $this->db->query($query, array($regionid))->result_array();
        
        // TODO: remove this part when the database bug has been resolved!
        $max = 0;
        foreach ($region['clans'] as $clan) {
            if ($clan['points'] > $max) {
                $max = $clan['points'];
                $region['leader'] = $clan['clanid'];
            }
        }
        
        return $region;
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
