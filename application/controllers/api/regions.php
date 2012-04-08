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
    
    function get($id = FALSE) {
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
                
                // NOTE: references did not work for some reason
                foreach ($region['clans'] as $key => $clan) {
                    if ($clan['points']) {
                        $region['clans'][$key]['possession'] = round($clan['points'] / $sum, 4) * 100;
                    } else {
                        $region['clans'][$key]['possession'] = 0;
                    }
                    
                    // clean up some non-public fields
                    unset($region['clans'][$key]['points']);
                    unset($region['clans'][$key]['battles']);
                    unset($region['clans'][$key]['capo']);
                }
            }
            
            // save cache
            $this->cache->save("api/regions.cache", $regions, 120);
        }
        
        // return the right region depending on the supplied id
        if ($id) {
            foreach ($regions as $region) {
                if ($region['regionid'] == $id) {
                    $this->output($region);
                    break;
                }
            }
        } else {
            $this->output($regions);
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
