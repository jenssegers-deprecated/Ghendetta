<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Map extends MY_Controller{

    public function index() {
		$this->load->model('clan_model');
	    $clans = $this->clan_model->get_all();

        $this->load->view('map', array('clans' => $clans));
    }
}