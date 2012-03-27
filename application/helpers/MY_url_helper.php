<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('static_url')) {
    function static_url($uri = '') {
        $CI = & get_instance();
        
        if ($CI->config->item('static_url')) {
            return $CI->config->slash_item('static_url') . ltrim($uri, '/');
        } else {
            return $CI->config->base_url($uri);
        }
    }
}