<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Result;
use WorldNewsGroup\Marketo\Client;

class Errors extends Model {
    public static $fields = [
        'date',
        'errors',
        'total'
    ];

    /**
     * @internal
     * 
     * Assembles Errors objects based on the Result object
     * 
     * @return Errors[] An array of Errors objects
     */
    public static function manufacture(Result $result) {
        $objects = [];

        foreach($result->getResults() as $r) {
            $objects[] = new Errors($r);
        }
        
        return $objects;
    }  
}