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

class Venues extends API_Controller {
    
    function index() {
        if ($user = $this->ghendetta->current_user()) {
            $fsqid = $user['fsqid'];
            
            // try from cache
            if (!$venues = $this->cache->get("api/venues.cache")) {
                // cache miss
                $this->load->model('venue_model');
                $venues = $this->venue_model->get_all_active();
                
                // save cache
                $this->cache->save("api/venues.cache", $venues, 300);
            }
            
            $this->output($venues);
        } else {
            $this->error('Not authenticated', 401);
        }
    }

}
