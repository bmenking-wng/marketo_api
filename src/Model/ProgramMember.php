<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Client;

class ProgramMember extends Model {
    public static $fields = [
        'id',
        'firstName',
        'lastName',
        'email',
        'updatedAt',
        'createdAt'
    ];

    public function __construct($import) {
        $this->values = $import;
    }

    public static function getProgramMemberFields() {
        return Client::send('GET', 'programs/members/schema/fields.json');
    }

    public static function describeProgramMember() {
        return Client::send('GET', 'program/members/describe.json');
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
