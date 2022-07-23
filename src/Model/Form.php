<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Result;
use WorldNewsGroup\Marketo\Client;

class Form extends Model {
    public static $fields = [
        'id',
        'status',
        'reasons'
    ];

    /**
     * @internal
     * 
     * Assembles objects based on the Result object
     * 
     * @return Form[]
     */
    public static function manufacture(Result $result) {
        $objects = [];

        foreach($result->getResults() as $r) {
            $objects[] = new Form($r);
        }
        
        return $objects;
    }  
}