<?php

class optout extends CI_Controller{

    function index(){
        $data = array();

        $data["action"] = "log out and delete all your personal data from the Ghendetta application";
        //to change: *secure* url to script which actually 
        //deletes db content & logs out user.
        $data["actionurl"] = "/optout" ;
        //to change: hard coded url to site_url
        $data["cancelurl"] = ( isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "http://ghendetta.be/" ) ;
        
        $this->load->view('warning',$data);
    }

}
