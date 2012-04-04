<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class category_model extends CI_Model {
    
    function insert($category) {
        $this->db->insert('categories', $category);
        return $this->db->insert_id();
    }
    
    function update($categoryid, $category) {
        return $this->db->where('categoryid', $categoryid)->update('categories', $category);
    }
    
    function get($categoryid) {
        return $this->db->where('categoryid', $categoryid)->get('categories')->row_array();
    }
    
    function count() {
        return $this->db->count_all('categories');
    }
    
    /**
     * Remove all categories
     */
    function truncate() {
        $this->db->truncate('categories');
    }

}
