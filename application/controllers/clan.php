<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    
class Clan extends CI_Controller {
    
    function index() {
        // this is no controller where unregistered users are allowed to hang out
        $user = $this->ghendetta->current_user();
        if (!$user) {
            redirect();
        }

        // load model & clan
        $this->load->model('clan_model');
        $clan = $this->clan_model->get($user['clanid']);
        
        // calculate the progress, start at 60 to start, and end with 100 at 23:15
        $now = time() ;
        $release = 1333055771 ; // 23:15
        $start   = 1332976478 ;   // 01:15 ofzo
        
        $progress = ($now - $start) * 1.0 / ($release - $start) ;
        $progress = floor( 60 + ( $progress * 40 )) ;

        echo $progress ;
        
        // setting data
        $data = array();
        $data['progress'] = $progress ;
        $data['clan'] = $clan ;

        // view
        $this->load->view('clan-waiting', $data);
    }
    
    function save() {
    
    }

}
