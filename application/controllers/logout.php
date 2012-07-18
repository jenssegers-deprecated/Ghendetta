<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class logout extends MY_Controller {
    
    function index() {
        
        if ($user = $this->auth->current_user()) {
            $this->auth->logout();
        }
        
        redirect();
    }

}

?>
