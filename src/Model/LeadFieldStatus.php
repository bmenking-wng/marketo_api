<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Result;
use WorldNewsGroup\Marketo\Client;

class LeadFieldStatus extends Model {
    public static $fields = [
        'name',
        'status'
    ];

    /**
     * @internal
     * 
     * Assembles objects based on the Result object
     * 
     * @return LeadFieldStatus[]
     */
    public static function manufacture(Result $result) {
        $objects = [];

        foreach($result->getResults() as $r) {
            $objects[] = new LeadFieldStatus($r);
        }
        
        return $objects;
    }  
}