<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Client;

class Opportunities extends Model {

    /**
     * getOpportunityFieldByName
     * 
     * Retrieves metadata for single opportunity field. 
     * Required Permissions: Read-Write Schema Standard Field, Read-Write Schema Custom Field
     * 
     */
    public static function getOpportunityFieldByName($fieldApiName) {
        return Client::send('GET', 'opportunities/schema/fields/' . $fieldApiName . '.json');
    }

    /**
     * getOpportunityFields
     * 
     * Retrieves metadata for all opportunity fields in the target instance. 
     * Required Permissions: Read-Write Schema Standard Field, Read-Write Schema Custom Field
     * 
     */
    public static function getOpportunityFields($batch_size = 300, $next_page_token = null) {
        if( $batch_size > 300 ) {
            throw new Exception('Batch size cannot exceed 300');
        }

        $query = [
            'batchSize'=>$batch_size
        ];

        if( !is_null($next_page_token) ) {
            $query['nextPageToken'] = $next_page_token;
        }

        return Client::send('GET', 'opportunities/schema/fields.json', ['query'=>$query]);
    }

    /**
     * getOpportunities
     * 
     * Returns a list of opportunities based on a filter and set of values. 
     * Required Permissions: Read-Only Opportunity, Read-Write Named Opportunity
     * 
     */
    public static function getOpportunities($filter_type, $filter_values, $fields = [], $batch_size = 300, $next_page_token = null) {
        $lookupObject = [
            'batchSize'=>$batch_size,
            'fields'=>$fields,
            'filterType'=>$filter_type,
            'input'=>$filter_values
        ];

        if( !is_null($next_page_token) ) {
            $lookupObject['nextPageToken'] = $next_page_token;
        }

        return Client::send('GET', 'opportunities.json', ['body'=>$lookupObject]);
    }

    /**
     * syncOpportunities
     * 
     * Allows inserting, updating, or upserting of opportunity records into the target instance. 
     * Required Permissions: Read-Write Named Opportunity
     * 
     */
    public static function syncOpportunities($custom_objects, $action = 'createOrUpdate', $dedupe_by = 'email') {
        $body = [
            'input'=>$custom_objects,
            'action'=>$action,
            'dedupeBy'=>$dedupe_by
        ];

        return Client::send('POST', 'opportunities.json', ['body'=>$body]);
    }

    /**
     * deleteOpportunities
     * 
     * Deletes a list of opportunity records from the target instance. Input records should only have one member, based on the value of 'dedupeBy'. 
     * Required Permissions: Read-Write Named Opportunity
     */
    public static function deleteOpportunities($ids, $delete_by = null) {
        $body = [
            'input'=>$ids
        ];

        if( !is_null($delete_by) ) {
            $body['deleteBy'] = $delete_by;
        }

        return Client::send('POST', 'opportunities/delete.json', ['body'=>$body]);
    }

    /**
     * describeOpportunity
     * 
     * Returns object and field metadata for Opportunity type records in the target instance. 
     * Required Permissions: Read-Only Opportunity, Read-Write Named Opportunity
     */
    public static function describeOpportunity() {
        return Client::send('GET', 'opportunities/describe.json');
    }

    /**
     * getOpportunityRoles
     * 
     * Returns a list of opportunity roles based on a filter and set of values. 
     * Required Permissions: Read-Only Opportunity, Read-Write Named Opportunity
     */
    public static function getOpportunityRoles($lookup_request, $filter_type, $filter_values, $fields = null, $batch_size = 300, $next_page_token = null) {
        if( $batch_size > 300 ) {
            throw new Exception('Batch size cannot exceed 300');
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

        return Client::send('GET', 'opportunities/roles.json', ['body'=>$body]);
    }

    /**
     * syncOpportunityRoles
     * 
     * Allows inserts, updates and upserts of Opportunity Role records in the target instance. 
     * Required Permissions: Read-Write Named Opportunity
     */
    public static function syncOpportunityRoles($custom_objects, $action = 'createOrUpdate', $dedupe_by = 'email') {
        $body = [
            'input'=>$custom_objects,
            'action'=>$action,
            'dedupeBy'=>$dedupe_by
        ];

        return Client::send('POST', 'opportunities/roles.json', ['body'=>$body]);
    }

    /**
     * deleteOpportunityRoles
     * 
     * Deletes a list of opportunities from the target instance. 
     * Required Permissions: Read-Write Named Opportunity
     */
    public static function deleteOpportunityRoles($input, $delete_by = null) {
        $body = [
            'input'=>$input
        ];

        if( !is_null($delete_by) ) {
            $body['deleteBy'] = $delete_by;
        }

        return Client::send('POST', 'opportunities/roles/delete.json', ['body'=>$body]);
    }

    /**
     * describeOpportunityRole
     * 
     * Returns object and field metadata for Opportunity Roles in the target instance. 
     * Required Permissions: Read-Only Opportunity, Read-Write Named Opportunity
     */
    public static function describeOpportunityRole() {
        return Client::send('GET', 'opportunities/roles/describe.json');
    }
}