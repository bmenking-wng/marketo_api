<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Result;

class StaticList extends Model {
    public static $fields = [
        'createdAt',
        'listId',
        'updatedAt'
    ];

    /**
     * @internal
     * 
     * Assembles Campaign objects based on the Result object
     * 
     * @return array An array of Campaign objects
     */
    public static function manufacture(Result $result) {
        $objects = [];

        foreach($result->getResults() as $r) {
            $objects[] = new StaticList($r);
        }
        
        return $objects;
    }  
}