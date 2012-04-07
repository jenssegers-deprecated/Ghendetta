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
            $this->ghendetta->optout();
        }
        
        redirect('https://foursquare.com/settings/connections');
    }
    
    function warning() {
        $data['message']  = 'When you click confirm all your precious battles will be gone and your clanmembers will be very disappointed. ';
        $data['message'] .= 'It is still possible to change your mind and click cancel. If not, you will be redirected to Foursquare ';
        $data['message'] .= 'after Ghendetta has deleted your battle history. On the Foursquare settings page ';
        $data['message'] .= 'you will be given the possibility to revoke Ghendetta from having any further access.';
        $data['action_url'] = site_url('optout/go');
        $data['cancel_url'] = site_url();
        
        $this->load->view('warning', $data);
    }
}

?>
