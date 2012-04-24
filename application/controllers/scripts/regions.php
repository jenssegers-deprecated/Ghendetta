<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Regions extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        
        $user = $this->auth->current_user();
        if (!($user && $user['admin']) && !$this->input->is_cli_request()) {
            show_error('You have not permission to access this page');
        }
    }
    
    function index() {
        $this->load->model('region_model');
        $regions = $this->region_model->get_all();
        
        foreach ($regions as $region) {
            $leader = $this->region_model->get_leader($region['regionid']);
            
            if ($leader) {
                $this->region_model->update($region['regionid'], array('leader' => $leader['clanid']));
                echo $region['name'] . ' -> ' . $leader['name'] . "\n";
            } else {
                $this->region_model->update($region['regionid'], array('leader' => 0));
                echo $region['name'] . ' -> no leader';
            }
        }
    }
}