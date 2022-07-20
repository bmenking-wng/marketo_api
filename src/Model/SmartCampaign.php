<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Result;
use WorldNewsGroup\Marketo\Client;

class SmartCampaign extends Model {
    public static $fields = [
        'createdAt',
        'smartCampaignId',
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
            $objects[] = new SmartCampaign($r);
        }
        
        return $objects;
    }  
}