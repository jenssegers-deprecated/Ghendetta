<?php

class debug extends CI_Controller{

    function view( $view ){
        $this->load->view($view);
    }

    function index(){
        redirect();
    }

}

?>
