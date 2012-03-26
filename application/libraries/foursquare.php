<?php

class Foursquare {
    
    private $settings, $ci;
    
    // contains the last error
    public $error = FALSE;
    
    function __construct() {
        $this->ci = &get_instance();
        
        // get config
        $this->ci->config->load('foursquare', TRUE);
        $this->settings = $this->ci->config->item('foursquare');
    }
    
    /**
     * Set the token to use for following request
     */
    function token() {
        return $this->ci->session->userdata('fsq_token');
    }
    
    /**
     * Get the current token
     * @param string $token
     */
    function set_token($token) {
        return $this->ci->session->set_userdata('fsq_token', $token);
    }
    
    /**
     * Authorization url
     * @param string $callback
     * @return string
     */
    function auth_url($callback = FALSE) {
        if (!$callback) {
            $callback = $this->settings['callback'];
        }
        
        return 'https://foursquare.com/oauth2/authenticate?client_id=' . $this->settings['client'] . '&response_type=code&redirect_uri=' . urlencode($callback);
    }
    
    /**
     * Get OAuth token
     * @param string $code
     * @return string
     */
    function request_token($code) {
        $url = 'https://foursquare.com/oauth2/access_token?client_id=' . $this->settings['client'] . '&client_secret=' . $this->settings['secret'] . '&grant_type=authorization_code&redirect_uri=' . urlencode($this->settings['callback']) . '&code=' . $code;
        $json = $this->_request($url);
        
        if (!isset($json->access_token)) {
            $this->error = 'Did not receive authentication token';
            return FALSE;
        }
        
        $this->set_token($json->access_token);
        return $json->access_token;
    }
    
    /**
     * Foursquare API request method
     * @param string $uri
     * @param array $data
     * @return Object
     */
    function api($uri, $data = array()) {
        if (!$token = $this->token()) {
            $this->error = 'No token available for API request';
            return FALSE;
        }
        
        $data['oauth_token'] = $token;
        $json = $this->_request('https://api.foursquare.com/v2/' . $uri . '?' . http_build_query($data));
        
        if (!$json) {
            $this->error = 'No response from Foursquare API';
            return FALSE;
        } elseif ($json->meta->code != 200) {
            $this->error = $json->meta->errorDetail;
            return FALSE;
        }
        
        return $json;
    }
    
    /**
     * Raw CURL request method
     * @param string $url
     * @return Object
     */
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