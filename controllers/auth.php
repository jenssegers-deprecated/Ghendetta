<?php

class Auth extends CI_Controller {
    
    private $client = '';
    private $callback = '';
    private $secret = '';
    
    function __construct() {
        parent::__construct();
        
        $this->load->library('session');
        $this->callback = urlencode($this->callback);
    }
    
    function index() {
        if ($this->session->userdata('oauth_token')) {
            $url = 'https://api.foursquare.com/v2/users/self/checkins?oauth_token=' . $this->session->userdata('oauth_token');
            $json = $this->_request($url);
            
            print_r($json);
        } else {
            redirect('https://foursquare.com/oauth2/authenticate?client_id=' . $this->client . '&response_type=code&redirect_uri=' . $this->callback);
        }
    }
    
    function callback() {
        if ($this->input->get('code')) {
            $code = $this->input->get('code');
            $this->session->set_userdata('oauth_code', $code);
            
            $url = 'https://foursquare.com/oauth2/access_token?client_id=' . $this->client . '&client_secret=' . $this->secret . '&grant_type=authorization_code&redirect_uri=' . $this->callback . '&code=' . $this->session->userdata('oauth_code');
            $json = $this->_request($url);
            
            if (isset($json->access_token)) {
                $this->session->set_userdata('oauth_token', $json->access_token);
                echo 'set auth_token: ' . $this->session->userdata('oauth_token');
            
            } else {
                echo 'error: ' . $json->error;
            }
        }
    }
    
    function _request($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPGET, TRUE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        
        $data = curl_exec($curl);
        curl_close($curl);
        
        return json_decode($data);
    }

}