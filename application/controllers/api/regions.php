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
        $this->load->model('region_model');
        $regions = $this->region_model->get_all_stats();
        
        $this->output($regions);
    }
    
    function get($id) {
        $this->load->model('region_model');
        $region = $this->region_model->get_stats($id);
        
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
