<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Venues extends CI_Controller {
    
    function __construct() {
        if (!$this->input->is_cli_request()) {
            show_error('Scripts can only be executed from CLI');
        }
    }
    
    function index($listid) {
        $this->load->model('venue_model');
        $venues = $this->venue_model->get_list($listid);
        
        $this->load->library('encrypt');
        
        foreach ($venues as $venue) {
            echo $venue['name'] . " -> " . $this->encrypt->encode($venue['venueid']) . "\n";
        }
    }
}