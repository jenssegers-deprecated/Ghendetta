<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    
class Email extends MY_Controller {
    
    function index() {
        header('location: mailto: info@ghendetta.be');
        
        if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']) {
            redirect($_SERVER['HTTP_REFERER'], 'refresh');
        } else {
            $this->output->set_output('<script>history.go(-1)</script>');
        }
    }
    
}