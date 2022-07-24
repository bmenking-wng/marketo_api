<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Client;
use WorldNewsGroup\Marketo\Result;

class ProgramMember extends Model {
    public static $fields = [
        'id',
        'firstName',
        'lastName',
        'email',
        'updatedAt',
        'createdAt'
    ];

    /**
     * @internal
     * 
     * Assembles objects based on the Result object
     * 
     * @return ProgramMember[]
     */
    public static function manufacture(Result $result) {
        $objects = [];

        foreach($result->getResults() as $r) {
            $objects[] = new ProgramMember($r);
        }
        
        return $objects;
    }  

    /**
     * Retrieves metadata for all program member fields in the target instance. 
     * Required Permissions: Read-Write Schema Standard Field, Read-Write Schema Custom Field
     * 
     * @return LeadField[] | null
     */
    public static function getProgramMemberFields() {
        return LeadField::manufacture(Client::send('GET', 'programs/members/schema/fields.json'));
    }

    /**
     * Returns metadata about program member objects in the target instance, including a list of all fields available for interaction via the APIs. 
     * Required Permissions: Read-Only Lead, Read-Write Lead
     * 
     * @return ProgramMemberAttribute2[] | null
     */
    public static function describeProgramMember() {
        return ProgramMemberAttribute2::manufacture(Client::send('GET', 'program/members/describe.json'));
    }
}

/*
GET /rest/v1/programs/members/schema/fields/{fieldApiName}.jsonGet Program Member Field by Name
POST /rest/v1/programs/members/schema/fields/{fieldApiName}.jsonUpdate Program Member Field

POST /rest/v1/programs/members/schema/fields.jsonCreate Program Member Fields
POST /rest/v1/programs/{programId}/members/status.jsonSync Program Member Status
GET /rest/v1/programs/{programId}/members.jsonGet Program Members
POST /rest/v1/programs/{programId}/members.jsonSync Program Member Data
POST /rest/v1/programs/{programId}/members/delete.jsonDelete Program Members
GET /rest/v1/programs/members/describe.jsonDescribe Program Member
*/
