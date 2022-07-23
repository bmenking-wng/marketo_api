<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Result;

class LeadAttribute extends Model {
    public static $fields = [
        'dataType',
        'displayName',
        'id',
        'length',
        'rest',
        'soap'
    ];

    /**
     * @internal
     * 
     * Assembles objects based on the Result object
     * 
     * @return LeadAttribute[]
     */
    public static function manufacture(Result $result) {
        $objects = [];

        foreach($result->getResults() as $r) {
            $objects[] = new LeadAttribute($r);
        }
        
        return $objects;
    }    
}