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
        return LeadField::manufacture(Client::send('GET', 'rest/v1/programs/members/schema/fields.json'));
    }

    /**
     * Returns metadata about program member objects in the target instance, including a list of all fields available for interaction via the APIs. 
     * Required Permissions: Read-Only Lead, Read-Write Lead
     * 
     * @return ProgramMemberAttribute2[] | null
     */
    public static function describeProgramMember() {
        return ProgramMemberAttribute2::manufacture(Client::send('GET', 'rest/v1/program/members/describe.json'));
    }

    /**
     * Returns a list of up to 300 program members on a list of values in a particular field. If you specify a filterType that is a custom field, 
     * the custom field’s dataType must be either “string” or “integer”. If you specify a filterType other than “leadId”, a maximum of 100,000 
     * program member records can be processed by the request. 
     * Required Permissions: Read-Only Lead, Read-Write Lead
     * 
     * @param   int         $program_id     The id of target program.
     * @param   string      $filter_type    The program member field to filter on. Any custom field (string or integer types only), "updatedAt", 
     *                                      or any searchable field. Searchable fields can be obtained via the Describe Program Member endpoint.
     * @param   string[]    $filter_values  A comma-separated list of values to filter on in the specified fields.
     * @param   string      $start_at       When using filterType=updatedAt, the start of date range filter (ISO 8601-format)
     * @param   string      $end_at         When using filterType=updatedAt, the end of date range filter (ISO 8601-format)
     * @param   string[]    $fields         A comma-separated list of lead fields to return for each record.
     * @param   int         $batch_size     The batch size to return. The max and default value is 300.
     * @param   string      $next_page_token    A token will be returned by this endpoint if the result set is greater than the batch size and 
     *                                          can be passed in a subsequent call through this parameter. See Paging Tokens for more info.
     * @return ProgramMember[] | null
     */
    public static function getProgramMembers($program_id, $filter_type, $filter_values, $start_at = null, $end_at = null, $fields = null, $batch_size = 300, $next_page_token = null) {
        $query = [
            'filterType'=>$filter_type,
            'filterValues'=>$filter_values,
            'batchSize'=>$batch_size
        ];

        if( !is_null($start_at) ) $query['startAt'] = $start_at;
        if( !is_null($end_at) ) $query['endAt'] = $end_at;
        if( !is_null($next_page_token) ) $query['nextPageToken'] = $next_page_token;
        if( !is_null($fields) ) $query['fields'] = $fields;

        return ProgramMember::manufacture(Client::send('GET', 'rest/v1/programs/' . $program_id . '/members.json', ['query'=>$query]));
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
