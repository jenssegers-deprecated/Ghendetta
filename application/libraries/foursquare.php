<?php

class Foursquare {
    
    private $settings, $ci;
    
    function __construct() {
        $this->ci = &get_instance();
        
        // get config
        $this->ci->config->load('foursquare', TRUE);
        $this->settings = $this->ci->config->item('foursquare');
    }
    
    function token() {
        return $this->ci->session->userdata('fsq_token');
    }
    
    function set_token($token) {
        return $this->ci->session->set_userdata('fsq_token', $token);
    }
    
    function id() {
        if ($this->ci->session->userdata('fsq_id')) {
            return $this->ci->session->userdata('fsq_id');
        }
        
        // fetch current user id
        $info = $this->api('users/self');
        $this->set_id($info->response->user->id);
        
        return $info->response->user->id;
    }
    
    function set_id($id) {
        return $this->ci->session->set_userdata('fsq_id', $id);
    }
    
    function auth_url($callback = FALSE) {
        if (!$callback) {
            $callback = $this->settings['callback'];
        }
        
        return 'https://foursquare.com/oauth2/authenticate?client_id=' . $this->settings['client'] . '&response_type=code&redirect_uri=' . urlencode($callback);
    }
    
    function request_token($code) {
        $url = 'https://foursquare.com/oauth2/access_token?client_id=' . $this->settings['client'] . '&client_secret=' . $this->settings['secret'] . '&grant_type=authorization_code&redirect_uri=' . urlencode($this->settings['callback']) . '&code=' . $code;
        $json = $this->_request($url);
        
        if (isset($json->access_token)) {
            $this->set_token($json->access_token);
            return $json->access_token;
        } else {
            show_error($json->error);
        }
    }
    
    function api($uri, $data = array()) {
        if (!$token = $this->token()) {
            show_error('Not authenticated');
        }
        
        $data['oauth_token'] = $token;
        
        $uri = 'https://api.foursquare.com/v2/' . $uri . '?' . http_build_query($data);
        return $this->_request($uri);
    }
    
    private function _request($url) {
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