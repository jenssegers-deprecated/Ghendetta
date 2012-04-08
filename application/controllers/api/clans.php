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
    
    function get($id) {
        // try from cache
        if (!$clans = $this->cache->get("api/clans.cache")) {
            // cache miss
            $this->load->model('clan_model');
            $clans = $this->clan_model->get_all_stats();
            
            // save cache
            $this->cache->save("api/clans.cache", $clans, 300);
        }
        
        // return the right clan depending on the supplied id
        if ($id) {
            foreach ($clans as $clan) {
                if ($clan['clanid'] == $id) {
                    $this->output($clan);
                    break;
                }
            }
        } else {
            $this->output($clans);
        }
    }
    
    function _remap($method) {
        switch ($method) {
            case 'index' :
                $this->get();
                break;
            default :
                $this->get($method);
        }
    }

}
