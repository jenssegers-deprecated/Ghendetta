<?php
/**
 * @copyright (C) 2012 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jens Segers <jens at iRail.be>
 * @author Hannes Van De Vreken <hannes at iRail.be>
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class conversion_foursquare extends CI_Driver {
    
    function user($object, $defaults = array()) {
        $user = $defaults;
        $user['fsqid'] = $object->id;
        $user['firstname'] = $object->firstName;
        $user['lastname'] = isset($object->lastName) ? $object->lastName : '';
        $user['email'] = isset($object->contact->email) ? $object->contact->email : '';
        $user['twitter'] = isset($object->contact->twitter) ? $object->contact->twitter : '';
        $user['picurl'] = isset($object->photo) ? $object->photo : '';
        
        return $user;
    }
    
    function checkin($object, $defaults = array()) {
        // checkin must have a valid venue
        if (!isset($object->venue) || !isset($object->venue->location->lng) || !isset($object->venue->location->lat)) {
            return FALSE;
        }
        
        $checkin = $defaults;
        $checkin['checkinid'] = $object->id;
        $checkin['date'] = $object->createdAt;
        $checkin['venueid'] = $object->venue->id;
        $checkin['lon'] = $object->venue->location->lng;
        $checkin['lat'] = $object->venue->location->lat;
        
        return $checkin;
    }
    
    function checkins($objects, $defaults = array()) {
        // sort checkins so oldest checkin gets inserted first
        usort($objects, array($this, 'cmp_checkins'));
        
        $checkins = array();
        foreach ($objects as $object) {
            // convert objects
            $checkins[] = $this->checkin($object, $defaults);
        }
        
        return $checkins;
    }
    
    function venue($object, $defaults = array()) {
        $venue = $defaults;
        $venue['venueid'] = $object->id;
        $venue['name'] = $object->name;
        $venue['lon'] = $object->location->lng;
        $venue['lat'] = $object->location->lat;
        
        if ($object->categories) {
            $category = reset($object->categories);
            $venue['categoryid'] = $category->id;
        }
        
        return $venue;
    }
    
    /**
     * Sorts checkins based on their created timestamp
     * @param checkin $a
     * @param checkin $b
     * @return number
     */
    private function cmp_checkins($a, $b) {
        if ($a->createdAt == $b->createdAt) {
            return 0;
        }
        return $a->createdAt < $b->createdAt ? -1 : 1;
    }

}