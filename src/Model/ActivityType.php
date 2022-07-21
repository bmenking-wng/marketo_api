<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Result;
use WorldNewsGroup\Marketo\Client;

class ActivityType extends Model {
    public static $fields = [
        'apiName',
        'attributes',
        'description',
        'id',
        'name',
        'primaryAttribute'
    ];

    /**
     * @internal
     * 
     * Assembles Campaign objects based on the Result object
     * 
     * @return ActivityType[]
     */
    public static function manufacture(Result $result) {
        $objects = [];

        foreach($result->getResults() as $r) {
            $objects[] = new ActivityType($r);
        }
        
        return $objects;
    }  
}