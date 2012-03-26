<?php

class Request_logger {
    
    function log() {
        $ci = &get_instance();
        
        $ci->load->library('user_agent');
        
        $request = array();
        $request['browser'] = $ci->agent->browser() . ' ' . $ci->agent->version();
        $request['os'] = $ci->agent->platform();
        $request['time'] = time();
        $request['ip'] = $ci->input->ip_address();
        $request['uri'] = $ci->uri->uri_string() ? $ci->uri->uri_string() : '/';
        $request['method'] = $ci->input->server('REQUEST_METHOD');
        
        $ci->load->model('request_model');
        $ci->request_model->insert($request);
    }

}