<?php

class View extends CI_Controller {
    
    function _remap($view) {
        $this->load->view($view);
    }
    
}