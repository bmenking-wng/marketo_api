<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Result;
use WorldNewsGroup\Marketo\Client;

class ObjectMetaData extends Model {
    public static $fields = [
        'createdAt',
        'dedupeFields',
        'description',
        'displayName',
        'pluralName',
        'fields',
        'idField',
        'apiName',
        'relationships',
        'searchableFields',
        'updatedAt',
        'state',
        'version'
    ];

    /**
     * @internal
     * 
     * Assembles objects based on the Result object
     * 
     * @return ObjectMetaData[]
     */
    public static function manufacture(Result $result) {
        $objects = [];

        foreach($result->getResults() as $r) {
            $objects[] = new ObjectMetaData($r);
        }
        
        return $objects;
    }  
}