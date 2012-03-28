<?php

class Debug extends CI_Controller {
    
    function view($view) {
        $this->load->view($view);
    }

}
