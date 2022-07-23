<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Client;
use WorldNewsGroup\Marketo\Result;
class LeadPartition extends Model {
    public static $fields = [
        'description',
        'id',
        'name'
    ];

    /**
     * @internal
     * 
     * Assembles objects based on the Result object
     * 
     * @return LeadPartition[]
     */
    public static function manufacture(Result $result) {
        $objects = [];

        foreach($result->getResults() as $r) {
            $objects[] = new LeadPartition($r);
        }
        
        return $objects;
    }  
}