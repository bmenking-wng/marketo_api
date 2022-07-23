<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Result;
use WorldNewsGroup\Marketo\Client;

class LeadChange extends Model {
    public static $fields = [
        'activityDate',
        'activityTypeId',
        'attributes',
        'campaignId',
        'fields',
        'id',
        'leadId',
        'marketoGUID'
    ];

    /**
     * @internal
     * 
     * Assembles objects based on the Result object
     * 
     * @return LeadChange[] An array of objects
     */
    public static function manufacture(Result $result) {
        $objects = [];

        foreach($result->getResults() as $r) {
            $objects[] = new LeadChange($r);
        }
        
        return $objects;
    }  
}