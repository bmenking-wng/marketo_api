<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Result;

class CustomActivity extends Model {
    public static $fields = [
        'activityDate',
        'activityTypeId',
        'apiName',
        'attributes',
        'errors',
        'id',
        'leadId',
        'marketoGUID',
        'primaryAttributeValue',
        'status'
    ];
    
    /**
     * @internal
     * 
     * Assembles objects based on the Result object
     * 
     * @return array An array of Campaign objects
     */
    public static function manufacture(Result $result) {
        $objects = [];

        foreach($result->getResults() as $r) {
            $objects[] = new CustomActivity($r);
        }
        
        return $objects;
    } 
}