<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Result;
use WorldNewsGroup\Marketo\Client;

class Program extends Model {
    public static $fields = [
        'id',
        'acquiredBy',
        'isExhausted',
        'membershipDate',
        'nurtureCadence',
        'progressionStatus',
        'reachedSuccess',
        'stream',
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
            $objects[] = new Program($r);
        }
        
        return $objects;
    }  
}