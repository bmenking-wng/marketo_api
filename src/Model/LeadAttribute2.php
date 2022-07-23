<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Result;

class LeadAttribute2 extends Model {
    public static $fields = [
        'name',
        'searchableFields',
        'fields'
    ];

    /**
     * @internal
     * 
     * Assembles objects based on the Result object
     * 
     * @return LeadAttribute2[]
     */
    public static function manufacture(Result $result) {
        $objects = [];

        foreach($result->getResults() as $r) {
            $objects[] = new LeadAttribute2($r);
        }
        
        return $objects;
    }    
}