<?php

class View extends MY_Controller {
    
    function _remap($view) {
        $this->load->view($view);
    }
    
}