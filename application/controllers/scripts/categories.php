<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Categories extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        
        $user = $this->ghendetta->current_user();
        if (!($user && $user['admin']) && !$this->input->is_cli_request()) {
            show_error('Scripts can only be executed from CLI');
        }
    }
    
    function index() {
        $json = $this->foursquare->api('venues/categories');
        
        $this->load->model('category_model');
        $this->category_model->truncate();
        
        foreach ($json->response->categories as $category) {
            $this->process_category($category);
        }
    }
    
    private function process_category($category, $parent = '') {
        $data = array();
        $data['categoryid'] = $category->id;
        $data['name'] = $category->name;
        $data['icon'] = $category->icon;
        $data['parent'] = $parent;
        
        $this->category_model->insert($data);
        
        // recursive child categories
        if (isset($category->categories) && count($category->categories)) {
            foreach ($category->categories as $subcategory) {
                $this->process_category($subcategory, $category->id);
            }
        }
    }
}