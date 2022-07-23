<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Result;
use WorldNewsGroup\Marketo\Client;

class ChangeLeadProgramStatus extends Model {
    public static $fields = [
        'id',
        'reasons',
        'status'
    ];

    /**
     * @internal
     * 
     * Assembles Campaign objects based on the Result object
     * 
     * @return ChangeLeadPorgramStatus[]
     */
    public static function manufacture(Result $result) {
        $objects = [];

        foreach($result->getResults() as $r) {
            $objects[] = new ChangeLeadProgramStatus($r);
        }
        
        return $objects;
    }  
}