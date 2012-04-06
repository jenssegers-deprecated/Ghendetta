<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class venues extends CI_Controller{

function index( $page ){
    
    $data = $this->db->query("select max( u.token ) as token, venueid as venueid from checkins c join users u on c.userid = u.fsqid group by venueid limit ?, 50", array( $page * 50 ))->result_array();
    
    foreach( $data as $row ){
        $url = "https://api.foursquare.com/v2/venues/" . $row['venueid'] . "?oauth_token=" . $row['token'] ;
        $venue = $this->_request( $url );

        if( isset( $venue->response ) && isset( $venue->response->venue ) ){
            $venue = $venue->response->venue ;
            
            echo "https://foursquare.com/v/" . $row['venueid'] . " , " ;
            echo str_replace( "," , "-" , $venue->name ) . " , " ;
            if( count( $venue->categories ) > 0 ){
                echo $venue->categories[0]->name ;
            }
            echo " , https://maps.google.com/?q=" . $venue->location->lat . "+" . $venue->location->lng . " , " ;
            echo $venue->stats->checkinsCount . " \n";
        }
    }
}


private function _request($url) {
    
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPGET, TRUE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    
    $data = curl_exec($curl);
    curl_close($curl);

    //echo $data ;
    
    return json_decode($data);
    
}

}

?>
