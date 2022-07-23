<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Result;
use WorldNewsGroup\Marketo\Client;

class CustomObject extends Model {
    public static $fields = [
        'marktoGUID',
        'reasons',
        'seq'
    ];

    /**
     * @internal
     * 
     * Assembles objects based on the Result object
     * 
     * @return CustomObject[]
     */
    public static function manufacture(Result $result) {
        $objects = [];

        foreach($result->getResults() as $r) {
            $objects[] = new CustomObject($r);
        }
        
        return $objects;
    } 
}