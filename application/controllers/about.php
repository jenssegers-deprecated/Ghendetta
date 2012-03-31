<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class About extends CI_Controller {
    
    function index() {
        
        if (time() < strtotime('31.03.2012 20:00')) {
            redirect('construction');
        } else {
            $this->load->view('about');
        }
    }

}
