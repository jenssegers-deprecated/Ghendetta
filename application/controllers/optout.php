<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Optout extends MY_Controller {
    
    function index() {
        if ($user = $this->auth->current_user()) {
            $this->warning();
        } else {
            redirect();
        }
    }
    
    function go() {
        if ($user = $this->auth->current_user()) {
            $this->load->model('user_model');
            $this->user_model->delete($user['fsqid']);
            
            $this->auth->logout();
        }
        
        redirect('https://foursquare.com/settings/connections');
    }
    
    function warning() {
        $data['message'] = 'When you click confirm your will let down all your clanmembers. ';
        $data['message'] .= 'It is still possible to change your mind and click cancel. If not, ';
        $data['message'] .= 'Ghendetta will forget about you and you will be redirected to your Foursquare settings. ';
        $data['message'] .= 'On the Foursquare settings page ';
        $data['message'] .= 'you will be given the possibility to revoke Ghendetta from having any further access.';
        $data['action_url'] = site_url('optout/go');
        $data['cancel_url'] = site_url();
        
        $this->load->view('warning', $data);
    }
}

?>
