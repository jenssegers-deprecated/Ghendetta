<?php

class region_model extends CI_Model {
    
    function insertRegion($region) {
        return $this->db->insert('regions', $region);
    }
    
    function insertCoords($coords) {
        return $this->db->insert('regioncoords', $coords);
    }
    
    function get_all() {
        $regions = $this->db->get('regions')->result_array();
        foreach ($regions as &$region) {
            $region['coords'] = $this->db->where('regionid', $region['regionid'])->get('regioncoords')->result_array();
        }
        
        return $regions;
    }
    
    function battlefield() {
        $this->load->model('clan_model');
        
        $results = $this->db->query('SELECT users.clanid, regionid, count(1) as count FROM checkins JOIN users ON checkins.userid = users.fsqid WHERE checkins.date >= ' . (time() - 604800) . ' GROUP BY checkins.regionid, users.clanid
ORDER BY regionid ASC, count DESC')->result_array();
        
        $winners = array();
        foreach ($results as $result) {
            $rid = $result['regionid'];
            
            if (!isset($winners[$rid])) {
                $winners[$rid] = $result;
            } else if ($winners[$rid]['count'] < $result['count']) {
                $winners[$rid] = $result;
            }
        }
        
        $regions = $this->get_all();
        foreach ($regions as &$region) {
            $rid = $region['regionid'];
            
            if (isset($winners[$rid])) {
                $winner = $winners[$rid];
                $region['winner'] = $this->clan_model->get($winner['clanid']);
            } else {
                $region['winner'] = FALSE;
            }
        }
        
        return $regions;
    }

}
