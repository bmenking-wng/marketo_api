<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Result;
use WorldNewsGroup\Marketo\Client;

class Opportunity extends Model {
    public static $fields = [
        'createdAt',
        'dedupeFields',
        'description',
        'displayName',
        'pluralName',
        'fields',
        'idField',
        'apiName',
        'relationships',
        'searchableFields',
        'updatedAt',
        'state',
        'version'
    ];

    /**
     * @internal
     * 
     * Assembles objects based on the Result object
     * 
     * @return Opportunity[]
     */
    public static function manufacture(Result $result) {
        $objects = [];

        foreach($result->getResults() as $r) {
            $objects[] = new Opportunity($r);
        }
        
        return $objects;
    } 

    /**
     * Retrieves metadata for single opportunity field. 
     * Required Permissions: Read-Write Schema Standard Field, Read-Write Schema Custom Field
     * 
     * @param   string      $field_api_name
     * 
     * @return LeadField[] | null
     */
    public static function getOpportunityFieldByName($field_api_name) {
        return LeadField::manufacture(Client::send('GET', 'rest/v1/opportunities/schema/fields/' . $field_api_name . '.json'));
    }

    /**
     * Retrieves metadata for all opportunity fields in the target instance. 
     * Required Permissions: Read-Write Schema Standard Field, Read-Write Schema Custom Field
     * 
     * @param   int     $batch_size
     * @param   string  $next_page_token
     * 
     * @return LeadField[] | null
     */
    public static function getOpportunityFields($batch_size = 300, $next_page_token = null) {
        if( $batch_size > 300 ) {
            throw new \Exception('Batch size cannot exceed 300');
        }

        $query = [
            'batchSize'=>$batch_size
        ];

        if( !is_null($next_page_token) ) {
            $query['nextPageToken'] = $next_page_token;
        }

        return LeadField::manufacture(Client::send('GET', 'rest/v1/opportunities/schema/fields.json', ['query'=>$query]));
    }

    /**
     * Returns a list of opportunities based on a filter and set of values. 
     * Required Permissions: Read-Only Opportunity, Read-Write Named Opportunity
     * 
     * @param   string      $filter_type
     * @param   array       $input
     * @param   string[]    $fields
     * @param   int         $batch_size
     * @param   string      $next_page_token
     * 
     * @return CustomObject[] | null
     */
    public static function getOpportunities($filter_type, $input, $fields = [], $batch_size = 300, $next_page_token = null) {
        $lookupObject = [
            'batchSize'=>$batch_size,
            'fields'=>$fields,
            'filterType'=>$filter_type,
            'input'=>$input
        ];

        if( !is_null($next_page_token) ) {
            $lookupObject['nextPageToken'] = $next_page_token;
        }

        return CustomObject::manufacture(Client::send('GET', 'rest/v1/opportunities.json', ['body'=>$lookupObject]));
    }

    /**
     * Allows inserting, updating, or upserting of opportunity records into the target instance. 
     * Required Permissions: Read-Write Named Opportunity
     * 
     * @param   array       $custom_objects
     * @param   string      $action
     * @param   string      $dedupe_by
     * 
     * @return CustomObject[] | null
     */
    public static function syncOpportunities($custom_objects, $action = 'createOrUpdate', $dedupe_by = 'email') {
        $body = [
            'input'=>$custom_objects,
            'action'=>$action,
            'dedupeBy'=>$dedupe_by
        ];

        return CustomObject::manufacture(Client::send('POST', 'rest/v1/opportunities.json', ['body'=>$body]));
    }

    /**
     * Deletes a list of opportunity records from the target instance. Input records should only have one member, based on the value of 'dedupeBy'. 
     * Required Permissions: Read-Write Named Opportunity
     * 
     * @param   array       $ids            List of input records
     * @param   string      $delete_by      Field to delete records by. Permissible values are idField or dedupeFields as indicated by the 
     *                                      result of the corresponding describe record.
     * 
     * @return CustomObject[] | null
     */
    public static function deleteOpportunities($ids, $delete_by = null) {
        $body = [
            'input'=>$ids
        ];

        if( !is_null($delete_by) ) {
            $body['deleteBy'] = $delete_by;
        }

        return CustomObject::manufacture(Client::send('POST', 'rest/v1/opportunities/delete.json', ['body'=>$body]));
    }

    /**
     * Returns object and field metadata for Opportunity type records in the target instance. 
     * Required Permissions: Read-Only Opportunity, Read-Write Named Opportunity
     * 
     * @return Opportunity[] | null
     */
    public static function describeOpportunity() {
        return Opportunity::manufacture(Client::send('GET', 'rest/v1/opportunities/describe.json'));
    }

    /**
     * Returns a list of opportunity roles based on a filter and set of values. 
     * Required Permissions: Read-Only Opportunity, Read-Write Named Opportunity
     * 
     * @return CustomObject[] | null
     */
    public static function getOpportunityRoles($lookup_request, $filter_type, $filter_values, $fields = null, $batch_size = 300, $next_page_token = null) {
        if( $batch_size > 300 ) {
            throw new \Exception('Batch size cannot exceed 300');
        }

        $body = [
            'batchSize'=>$batch_size,
            'filterType'=>$filter_type,
            'filterValues'=>$filter_values
        ];

        if( !is_null($fields) ) {
            $body['fields'] = $fields;
        }

        if( !is_null($next_page_token) ) {
            $body['nextPageToken'] = $next_page_token;
        }

        return CustomObject::manufacture(Client::send('GET', 'rest/v1/opportunities/roles.json', ['body'=>$body]));
    }

    /**
     * Allows inserts, updates and upserts of Opportunity Role records in the target instance. 
     * Required Permissions: Read-Write Named Opportunity
     * 
     * @param   array   $custom_objects
     * @param   string  $action
     * @param   string  $dedupe_by
     * 
     * @return CustomObject[] | null
     */
    public static function syncOpportunityRoles($custom_objects, $action = 'createOrUpdate', $dedupe_by = 'email') {
        $body = [
            'input'=>$custom_objects,
            'action'=>$action,
            'dedupeBy'=>$dedupe_by
        ];

        return CustomObject::manufacture(Client::send('POST', 'rest/v1/opportunities/roles.json', ['body'=>$body]));
    }

    /**
     * Deletes a list of opportunities from the target instance. 
     * Required Permissions: Read-Write Named Opportunity
     * 
     * @param   array   $input
     * @param   string  $delete_by
     * 
     * @return CustomObject[] | null
     */
    public static function deleteOpportunityRoles($input, $delete_by = null) {
        $body = [
            'input'=>$input
        ];

        if( !is_null($delete_by) ) {
            $body['deleteBy'] = $delete_by;
        }

        return CustomObject::manufacture(Client::send('POST', 'rest/v1/opportunities/roles/delete.json', ['body'=>$body]));
    }

    /**
     * Returns object and field metadata for Opportunity Roles in the target instance. 
     * Required Permissions: Read-Only Opportunity, Read-Write Named Opportunity
     * 
     * @return Opportunity[] | null
     */
    public static function describeOpportunityRole() {
        return Opportunity::manufacture(Client::send('GET', 'rest/v1/opportunities/roles/describe.json'));
    }
}