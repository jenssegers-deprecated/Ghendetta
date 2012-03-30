<?php

class checkin_model extends CI_Model {
    
    function insert($checkin) {
        // get region leader before checkin
        $this->load->model('region_model');
        $region_before = $this->region_model->get_leader($checkin['regionid']);
        
        // insert checkin
        $this->db->insert('checkins', $checkin);
        $checkinid = $this->db->insert_id();
        
        // get user with points
        $this->load->model('user_model');
        $self = $this->user_model->get_stats($checkin['userid']);
        
        // security
        if ($self) {
            // get clan capo
            $this->load->model('clan_model');
            $capo = $this->clan_model->get_capo($self['clanid']);
            
            // new capo
            if ($self['points'] > $capo['points'] && $capo['clanid'] == $self['clanid']) {
                $this->clan_model->update($self['clanid'], array('capo' => $self['fsqid']));
            }
        }
        
        // check for different region leader
        $region_after = $this->region_model->get_leader($checkin['regionid']);
        if ($region_after['clanid'] != $region_before['clanid']) {
            $this->region_model->update($checkin['regionid'], array('leader' => $region_after['clanid']));
        
            // TODO: insert notification
            // ...
        }
    }
    
    function exists($checkinid) {
        return $this->db->where('checkinid', $checkinid)->count_all_results('checkins') != 0;
    }
    
    function get_all() {
        return $this->db->get('checkins')->result_array();
    }
    
    function get_since($userid, $since) {
        return $this->db->where('userid', $userid)->where('date >=', $since)->get('checkins')->result_array();
    }
    
    function get_unique_since($userid, $since) {
        return $this->db->where('userid', $userid)->where('date >=', $since)->group_by('venueid')->get('checkins')->result_array();
    }
    
    function get_last($userid) {
        return $this->db->where('userid', $userid)->order_by('date', 'desc')->get('checkins')->row_array();
    }
    
    function count($userid = FALSE) {
        if ($userid) {
            return $this->db->where('userid', $userid)->count_all_results('checkins');
        } else {
            return $this->db->count_all('checkins');
        }
    }

}