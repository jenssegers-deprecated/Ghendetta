<?php

class Debug extends CI_Controller {
    
    function index() {
        redirect();
    }
    
    function view($view) {
        $this->load->view($view);
    }

}

?>
