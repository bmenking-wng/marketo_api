<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Result;
use WorldNewsGroup\Marketo\Client;

class ProgramMemberAttribute2 extends Model {
    public static $fields = [
        'name',
        'description',
        'createdAt',
        'updatedAt',
        'dedupeFields',
        'searchableFields',
        'fields'
    ];

    /**
     * @internal
     * 
     * Assembles objects based on the Result object
     * 
     * @return ProgramMemberAttribute2[]
     */
    public static function manufacture(Result $result) {
        $objects = [];

        foreach($result->getResults() as $r) {
            $objects[] = new ProgramMemberAttribute2($r);
        }
        
        return $objects;
    }  
}