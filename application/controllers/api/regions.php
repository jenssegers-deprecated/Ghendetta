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

class Regions extends API_Controller {
    
    function index() {
        // try from cache
        if (!$regions = $this->cache->get("api/regions.cache")) {
            // cache miss
            $this->load->model('region_model');
            $regions = $this->region_model->get_all_stats();
            
            // add possession field
            foreach ($regions as &$region) {
                $sum = 0;
                foreach ($region['clans'] as $clan) {
                    $sum += $clan['points'];
                }
                
                foreach ($region['clans'] as &$clan) {
                    if ($clan['points']) {
                        $clan['possession'] = round($clan['points'] / $sum, 4) * 100;
                    } else {
                        $clan['possession'] = 0;
                    }
                    
                    unset($clan['points']);
                    unset($clan['battles']);
                    unset($clan['capo']);
                }
            }
            
            // save cache
            $this->cache->save("api/regions.cache", $regions, 120);
        }
        
        $this->output($regions);
    }
    
    function get($id) {
        // try from cache
        if (!$region = $this->cache->get("api/region-$id.cache")) {
            // cache miss
            $this->load->model('region_model');
            $region = $this->region_model->get_stats($id);
            
            // save cache
            $this->cache->save("api/region-$id.cache", $region, 120);
        }
        
        if ($region) {
            $this->output($region);
        } else {
            $this->error('Region not found', 404);
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
