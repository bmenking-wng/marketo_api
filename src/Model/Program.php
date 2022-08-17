<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Result;
use WorldNewsGroup\Marketo\Client;

class Program extends Model {
    public static $fields = [
        'id',
        'acquiredBy',
        'isExhausted',
        'membershipDate',
        'nurtureCadence',
        'progressionStatus',
        'reachedSuccess',
        'stream',
        'updatedAt'
    ];

    /**
     * @internal
     * 
     * Assembles objects based on the Result object
     * 
     * @return Program[]
     */
    public static function manufacture(Result $result) {
        $objects = [];

        foreach($result->getResults() as $r) {
            $objects[] = new Program($r);
        }
        
        return $objects;
    }  

    /**
     * Retrieves the program record for the given id. 
     * Required Permissions: Read-Only Assets, Read-Write Assets
     * 
     * @param   int     $program_id     id.
     * 
     * @return Program[] | null
     */
    public static function getProgramById($program_id) {
        return Program::manufacture(Client::send('GET', 'rest/asset/v1/program/' . $program_id . '.json'));
    }
}