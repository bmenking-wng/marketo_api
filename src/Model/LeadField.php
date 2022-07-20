<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Result;
use WorldNewsGroup\Marketo\Client;

class LeadField extends Model {
    public static $fields = [
        'displayName',
        'name',
        'description',
        'dataType',
        'length',
        'isHidden',
        'isHtmlEncodingInEmail',
        'isSensitive',
        'isCustom',
        'isApiCreated'
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
            $objects[] = new LeadField($r);
        }
        
        return $objects;
    }  
}