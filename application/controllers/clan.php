<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Clan extends CI_Controller {
    
    function index() {
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
        
        if ($user = $this->ghendetta->current_user()) {
            $clanid = $user['clanid'];
            
            // try from cache
            if (!$data = $this->cache->get("api/members-$clanid.cache")) {
                // cache miss
                $this->load->model('clan_model');
                $members = $this->clan_model->get_members($clanid);
                $clan = $this->clan_model->get_stats($clanid);
                
                $data = array('members' => $members, 'clan' => $clan, 'user' => $user);
                
                // save cache
                $this->cache->save("api/members-$clanid.cache", $data, 120);
            }
            
            $this->load->view('clan', $data);
        } else {
            redirect();
        }
    }

}