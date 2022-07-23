<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Client;
use WorldNewsGroup\Marketo\Result;

class Usage extends Model {
    public static $fields = [
        'date',
        'total',
        'users'
    ];

    /**
     * @internal
     * 
     * Assembles Campaign objects based on the Result object
     * 
     * @return Usage[] 
     */
    public static function manufacture(Result $result) {
        $objects = [];

        foreach($result->getResults() as $r) {
            $objects[] = new Usage($r);
        }
        
        return $objects;
    }  

    /**
     * Retrieves a count of each error type they have encountered in the current day. 
     * Required Permissions: None
     * 
     * @return Errors[] | null
     */
    public static function getDailyErrors() {
        return Errors::manufacture(Client::send('GET', 'stats/errors.json'));
    }

    /**
     * Returns a count of each error type they have encountered in the past 7 days. 
     * Required Permissions: None
     * 
     * @return Errors[] | null
     */
    public static function getWeeklyErrors() {
        return Errors::manufacture(Client::send('GET', 'stats/errors/last7days.json'));
    }

    /**
     * Returns the number of calls consumed for the day. 
     * Required Permissions: None
     * 
     * @return Usage[] | null
     */
    public static function getDailyUsage() {
        return Usage::manufacture(Client::send('GET', 'stats/usage.json'));
    }

    /**
     * Returns the number of calls consumed in the past 7 days. 
     * Required Permissions: None
     * 
     * @return Usage[] | null
     */
    public static function getWeeklyUsage() {
        return Usage::manufacture(Client::send('GET', 'stats/usage/last7days.json'));
    }    
}