<?php


class Test extends CI_Controller{

    function index(){
        
        $url = "http://ghendetta/push/" ;
        $data = array();
        $data["secret"] = "secret" ;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_NOBODY, FALSE);
        curl_setopt($curl, CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data );
        
        $data = curl_exec($curl);
        curl_close($curl);
        
        print_r( $data );
    }

}

?>
