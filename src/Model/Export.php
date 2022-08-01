<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Result;
use WorldNewsGroup\Marketo\Client;

class Export extends Model {
    public static $fields = [
        'createdAt',
        'errorMsg',
        'exportId',
        'fileSize',
        'fileChecksum',
        'finishedAt',
        'format',
        'numberOfRecords',
        'queuedAt',
        'startedAt',
        'status'
    ];

    /**
     * @internal
     * 
     * Assembles objects based on the Result object
     * 
     * @return Export[]
     */
    public static function manufacture(Result $result) {
        $objects = [];

        foreach($result->getResults() as $r) {
            $objects[] = new Export($r);
        }
        
        return $objects;
    }  
}