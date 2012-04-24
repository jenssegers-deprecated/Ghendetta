<?php

class Test extends CI_Controller {
    
    function index() {
        
        $this->load->model('checkin_model');
        $this->load->model('venue_model');
        
        $venueid = '4b003795f964a520993b22e3';
        $venue = $this->venue_model->get($venueid);
        
        $checkin = array();
        $checkin['checkinid'] = rand(1,100000);
        $checkin['userid'] = '3682040';
        $checkin['date'] = time();
        $checkin['venueid'] = $venueid;
        $checkin['lon'] = $venue['lon'];
        $checkin['lat'] = $venue['lat'];
        
        $this->checkin_model->insert($checkin);
        
    }
    
}