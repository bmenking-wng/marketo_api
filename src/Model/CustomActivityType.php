<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Result;

class CustomActivityType extends Model {
    public static $fields = [
        'apiName',
        'attributes',
        'createdAt',
        'description',
        'filterName',
        'id',
        'name',
        'primaryAttribute',
        'status',
        'triggerName',
        'updatedAt'
    ];
    
    /**
     * @internal
     * 
     * Assembles objects based on the Result object
     * 
     * @return CustomActivityType[]
     */
    public static function manufacture(Result $result) {
        $objects = [];

        foreach($result->getResults() as $r) {
            $objects[] = new CustomActivityType($r);
        }
        
        return $objects;
    } 
}