<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Venues_hash extends CI_Controller {
    
    function __construct() {
        parent::__construct();

        $user = $this->ghendetta->current_user();
        if (!($user && $user['admin']) && !$this->input->is_cli_request()) {
            show_error('Scripts can only be executed from CLI');
        }
    }
    
    function index($listid = FALSE) {
        if (!$listid) {
            return FALSE;
        }
        
        $this->load->model('venue_model');
        $venues = $this->venue_model->get_list($listid);
        
        foreach ($venues as $venue) {
            $code = $this->venue_model->generate_code($venue['venueid']);
            
            echo $venue['name'] . " -> " . $code . "\n";
            echo "\t" . site_url('foursquare/checkin/' . $venue['venueid'] . '/' . $code) . "\n";
        }
    }
}