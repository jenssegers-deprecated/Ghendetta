<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once (APPPATH . 'core/API_Controller.php');

class Clans extends API_Controller {
    
    function index() {
        // try from cache
        if (!$clans = $this->cache->get("api/clans.cache")) {
            // cache miss
            $this->load->model('clan_model');
            $clans = $this->clan_model->get_all_stats();
            
            // save cache
            $this->cache->save("api/clans.cache", $clans, 60);
        }
        
        $this->output($clans);
    }
    
    function get($id) {
        // try from cache
        if (!$clan = $this->cache->get("api/clan-$id.cache")) {
            $this->load->model('clan_model');
            $clan = $this->clan_model->get_stats($id);
            
            // save cache
            $this->cache->save("api/clan-$id.cache", $clan, 60);
        }
        
        if ($clan) {
            $this->output($clan);
        } else {
            $this->error('Clan not found', 404);
        }
    }
    
    function _remap($method) {
        switch ($method) {
            case 'index' :
                $this->index();
                break;
            default :
                $this->get($method);
        }
    }

}
