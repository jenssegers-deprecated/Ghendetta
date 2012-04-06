<?php

class Push extends CI_Controller{


    function index(){
        
        echo $this->input->post('secret');

        echo file_get_contents('php://input');

    }

}


