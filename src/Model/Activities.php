<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Client;

class Activities extends Model {
    /**
     * getLeadActivities
     * 
     * Returns a list of activities from after a datetime given by the nextPageToken parameter. Also allows for filtering by lead static list membership, or by a list of up to 30 lead ids. 
     * Required Permissions: Read-Only Activity, Read-Write Activity
     */
    public static function getLeadActivities($next_page_token, $activity_type_ids, $asset_ids = [], $list_id = [], $lead_ids = [], $batch_size = 300) {
        $query = [
            'nextPageToken'=>$next_page_token,
            'activityTypeIds'=>$activity_type_ids,
            'batchSize'=>$batch_size
        ];

        if( !empty($asset_ids) ) $query['assetIds'] = $asset_ids;
        if( !empty($list_id) ) $query['listId'] = $list_id;
        if( !empty($lead_ids) ) $query['leadIds'] = $lead_ids;
        
        return Client::send('GET', 'activities.json', ['query'=>$query]);
    }

    /**
     * getDeletedLeads
     * 
     * Returns a list of leads deleted after a given datetime. Deletions greater than 14 days old may be pruned. 
     * Required Permissions: Read-Only Activity, Read-Write Activity
     */
    public static function getDeletedLeads($next_page_token, $batch_size = 300) {
        $query = [
            'batchSize'=>$batch_size,
            'nextPageToken'=>$next_page_token
        ];

        return Client::send('GET', 'deletedleads.json', ['query'=>$query]);
    }

    /**
     * addCustomActivities
     * 
     * Allows insertion of custom activities associated to given lead records. Requires provisioning of custom activity types to utilize. 
     * Required Permissions: Read-Write Activity
     */
    public static function addCustomActivities($custom_activities) {
        return Client::send('POST', 'activities/external.json', ['body'=>$custom_activities]);
    }

    /**
     * createCustomActivityType
     * 
     * Creates a new custom activity type draft in the target instance. 
     * Required Permissions: Read-Write Activity Metadata
     */
    public static function createCustomActivityType($custom_activities) {
        return Client::send('POST', 'activities/external/type.json', ['body'=>$custom_activities]);
    }

    /**
     * updateCustomActivityType
     * 
     * Updates the target custom activity type. All changes are applied to the draft version of the type. 
     * Required Permissions: Read-Write Activity Metadata
     */
    public static function updateCustomActivityType($api_name, $custom_activity_type) {
        return Client::send('POST', 'activities/external/type/' . $api_name . '.json', ['body'=>$custom_activity_type]);
    }

    /**
     * approveCustomActivityType
     * 
     * Approves the current draft of the type, and makes it the live version. This will delete the current live version of the type. Required Permissions: Read-Write Activity Metadata
     */
    public static function approveCustomActivityType($api_name) {
        return Client::send('POST', 'activities/external/type/' . $api_name . '/approve.json');
    }

    /**
     * createCustomActivityTypeAttributes
     * 
     * Adds activity attributes to the target type. These are added to the draft version of the type. 
     * Required Permissions: Read-Write Activity Metadata
     */
    public static function createCustomActivityTypeAttributes($api_name, $custom_activity_type) {
        return Client::send('POST', 'activities/external/type/' . $api_name . '/create.json', ['body'=>$custom_activity_type]);
    }

    /**
     * deleteCustomActivityTypeAttributes
     * 
     * Deletes the target attributes from the custom activity type draft. The apiName of each attribute is the primary key for the update. 
     * Required Permissions: Read-Write Activity Metadata
     */
    public static function deleteCustomActivityTypeAttributes($api_name, $custom_activity_type_attributes) {
        return Client::send('POST', 'activities/external/type/' . $api_name . '/attributes/delete.json', ['body'=>$custom_activity_type_attributes]);
    }

    /**
     * updateCustomActivityTypeAttributes
     * 
     * Updates the attributes of the custom activity type draft. The apiName of each attribute is the primary key for the update. 
     * Required Permissions: Read-Write Activity Metadata
     */
    public static function updateCustomActivityTypeAttributes($api_name, $custom_activity_type_attributes) {
        return Client::send('POST', 'activities/external/type/' . $api_name . '/attributes/update.json', ['body'=>$custom_activity_type_attributes]);
    }

    /**
     * deleteCustomActivityType
     * 
     * Deletes the target custom activity type. The type must first be removed from use by any assets, such as triggers or filters. Required Permissions: Read-Write Activity Metadata
     */
    public static function deleteCustomActivityType($api_name) {
        return Client::send('POST', 'activities/external/type/' . $api_name . '/delete.json');
    }

    /**
     * describeCustomActivityType
     * 
     * Returns metadata for a specific custom activity type. 
     * Required Permissions: Read-Only Activity Metadata, Read-Write Activity Metadata
     */
    public static function describeCustomActivityType($api_name, $draft = false) {
        return Client::send('GET', 'activities/external/type/' . $api_name . '/describe.json', ['query'=>['draft'=>$draft]]);
    }

    /**
     * discardCustomActivityTypeDraft
     * 
     * Discards the current draft of the custom activity type. 
     * Required Permissions: Read-Write Activity Metadata
     */
    public static function discardCustomActivityTypeDraft($api_name) {
        return Client::send('POST', 'activities/external/type/' . $api_name . '/discardDraft.json');
    }

    /**
     * getCustomActivityTypes
     * 
     * Returns metadata regarding custom activities provisioned in the target instance. 
     * Required Permissions: Read-Only Activity Metadata, Read-Write Activity Metadata
     */
    public static function getCustomActivityTypes() {
        return Client::send('GET', 'activities/external/types.json');
    }

    /**
     * getLeadChanges
     * 
     * Returns a list of Data Value Changes and New Lead activities after a given datetime. 
     * Required Permissions: Read-Only Activity, Read-Write Activity
     */
    public static function getLeadChanges($next_page_token, $fields, $list_id = [], $lead_ids = [], $batch_size = 300) {
        $query = [
            'nextPageToken'=>$next_page_token,
            'fields'=>$fields,
            'batchSize'=>$batch_size
        ];

        if( !empty($list_id) ) $query['listId'] = $list_id;
        if( !empty($lead_ids) ) $query['leadIds'] = $lead_ids;

        return Client::send('GET', 'activities/leadchanges.json', ['query'=>$query]);
    }

    /**
     * getPagingToken
     * 
     * Returns a paging token for use in retrieving activities and data value changes. 
     * Required Permissions: Read-Only Activity, Read-Write Activity
     */
    public static function getPagingToken($since) {
        return Client::send('GET', 'activities/pagingtoken.json', ['query'=>['sinceDatetime'=>$since]]);
    }

    /**
     * getActivityTypes
     * 
     * Returns a list of available activity types in the target instance, along with associated metadata of each type.
     * Required Permissions: Read-Only Activity, Read-Write Activity
     */
    public static function getActivityTypes() {
        return Client::send('GET', 'activities/types.json');
    }

}