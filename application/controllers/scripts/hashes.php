<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Hashes extends CI_Controller {
    
    function __construct() {
        parent::__construct();

        $user = $this->auth->current_user();
        if (!($user && $user['admin']) && !$this->input->is_cli_request()) {
            show_error('Scripts can only be executed from CLI');
        }
    }
    
    function index($listid = FALSE) {
        if (!$listid) {
            return FALSE;
        }
        
        $this->load->model('list_model');
        $this->load->model('special_model');
        
        $venues = $this->list_model->get_specials($listid);
        
        foreach ($venues as $venue) {
            $code = $this->special_model->generate_code($venue['venueid']);
            
            echo $venue['name'] . " -> " . $code . "\n";
            echo "\t" . site_url('foursquare/checkin/' . $venue['venueid'] . '/' . $code) . "\n";
        }
    }
}