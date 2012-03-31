<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */
class optout extends CI_Controller{

    function index(){
        
        //tbd
        
        $confirmed = FALSE ;
        
        if( $confirmed ){
            if ( $user = $this->ghendetta->current_user() ) {
                $this->ghendetta->unset_user();
            }
        }else{
            $data = array();
            
            $data["action"] = "log out and delete all your personal data from the Ghendetta application";
            //to change: *secure* url to script which actually 
            //deletes db content & logs out user.
            $data["actionurl"] = "/optout" ;
            //to change: hard coded url to site_url
            $data["cancelurl"] = "http://ghendetta.be" ;//$this->site_url() ;
            
            $this->load->view('warning',$data);
        }
    }
}

?>
