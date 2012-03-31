<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */
class logout extends CI_Controller{

    function index(){

        if ( $user = $this->ghendetta->current_user() ) {
            $this->ghendetta->unset_user();
        }
        
        redirect();
    }

}

?>
