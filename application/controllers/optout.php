<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Optout extends CI_Controller {
    
    function index() {
        $this->warning();
    }
    
    function go() {
        // TODO: remove all user data
        
        if ($user = $this->ghendetta->current_user()) {
            $this->ghendetta->logout();
        }
        
        redirect('https://foursquare.com/settings/connections');
    }
    
    function warning() {
        $data['action'] = 'log out and delete all your personal data from the Ghendetta application';
        $data['action_url'] = site_url('optout/go');
        $data['cancel_url'] = site_url();
        
        $this->load->view('warning', $data);
    }
}

?>
