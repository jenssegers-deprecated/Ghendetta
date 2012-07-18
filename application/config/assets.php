<?php
/*
 *---------------------------------------------------------------
 * Assets configuration
 *---------------------------------------------------------------
 *
 * Define your assets per environment in this configuration file
 */

switch (ENVIRONMENT) {
    
    case 'production' :
        $config['mapbox'][]        = 'js/mapbox.min.js';
        $config['application'][]   = 'js/application.min.js';
        $config['styles'][]        = 'css/styles.min.css';
        break;
    
    default :
        $config['mapbox'][]        = 'js/mapbox/leaflet.js';
        $config['mapbox'][]        = 'js/mapbox/wax.js';
        $config['mapbox'][]        = 'js/mapbox.js';
        $config['application'][]   = 'js/application.js';
        $config['styles'][]        = 'css/1140.css';
        $config['styles'][]        = 'css/styles.css';
        
}