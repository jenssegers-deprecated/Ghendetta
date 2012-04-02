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

class Requests extends API_Controller {
    
    function index() {
        $this->get('regular');
    }
    
    function get($type) {
        // try from cache
        if (!$requests = $this->cache->get("api/requests-$type.cache")) {
            // cache miss
            $this->load->model('request_model');
            $requests = $this->request_model->get_daily($type);
        
            // save cache
            $this->cache->save("api/requests-$type.cache", $requests, 60);
        }
        
        $this->output($requests);
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