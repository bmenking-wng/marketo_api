<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Result;
use WorldNewsGroup\Marketo\Client;

class Activity extends Model {
    public static $fields = [
        'activityDate',
        'activityTypeId',
        'attributes',
        'campaignId',
        'id',
        'leadId',
        'marketoGUID',
        'primaryAttributeValue',
        'primaryAttributeValueId'
    ];
    
    /**
     * @internal
     * 
     * Assembles objects based on the Result object
     * 
     * @return Activity[]
     */
    public static function manufacture(Result $result) {
        $objects = [];

        foreach($result->getResults() as $r) {
            $objects[] = new Activity($r);
        }
        
        return $objects;
    } 

    /**
     * Returns a list of activities from after a datetime given by the nextPageToken parameter. Also allows for filtering by lead static list membership, or by a list of up to 30 lead ids. 
     * Required Permissions: Read-Only Activity, Read-Write Activity
     * 
     * @param   string      $next_page_token        Token representation of a datetime returned by the Get Paging Token endpoint. This endpoint will return activities after this datetime.
     * @param   int[]       $activity_type_ids      An array of activity type ids. These can be retrieved with the Get Activity Types API.
     * @param   int[]       $asset_ids              Id of the primary asset for an activity. This is based on the primary asset id of a given activity type. 
     *                                              Should only be used when a single activity type is set.
     * @param   int         $list_id                Id of a static list. If set, will only return activities of members of this static list.
     * @param   int[]       $lead_ids               An array of lead ids. If set, will only return activities of the leads with these ids. Allows up to 30 entries.
     * @param   int         $batch_size             Maximum number of records to return. Maximum and default is 300.
     * 
     * @return Activity[] | null
     */
    public static function getLeadActivities($next_page_token, $activity_type_ids, $asset_ids = [], $list_id = null, $lead_ids = [], $batch_size = 300) {
        $query = [
            'nextPageToken'=>$next_page_token,
            'activityTypeIds'=>$activity_type_ids,
            'batchSize'=>$batch_size
        ];

        if( !empty($asset_ids) ) $query['assetIds'] = $asset_ids;
        if( !is_null($list_id) ) $query['listId'] = $list_id;
        if( !empty($lead_ids) ) $query['leadIds'] = $lead_ids;
        
        return Activity::manufacture(Client::send('GET', 'activities.json', ['query'=>$query]));
    }

    /**
     * Returns a list of leads deleted after a given datetime. Deletions greater than 14 days old may be pruned. 
     * Required Permissions: Read-Only Activity, Read-Write Activity
     * 
     * @param   string      $next_page_token    Token representation of a datetime returned by the Get Paging Token endpoint. This endpoint will return activities after this datetime.   
     * @param   int         $batch_size         Maximum number of records to return. Maximum and default is 300.
     * 
     * @return Activity[] | null
     */
    public static function getDeletedLeads($next_page_token, $batch_size = 300) {
        $query = [
            'batchSize'=>$batch_size,
            'nextPageToken'=>$next_page_token
        ];

        return Activity::manufacture(Client::send('GET', 'deletedleads.json', ['query'=>$query]));
    }

    /**
     * Allows insertion of custom activities associated to given lead records. Requires provisioning of custom activity types to utilize. 
     * Required Permissions: Read-Write Activity
     * 
     * @param   array   $custom_activities      List of custom activities to insert.
     * 
     * @return CustomActivity[] | null
     */
    public static function addCustomActivities($custom_activities) {
        return CustomActivity::manufacture(Client::send('POST', 'activities/external.json', ['body'=>['input'=>$custom_activities]]));
    }

    /**
     * Creates a new custom activity type draft in the target instance. 
     * Required Permissions: Read-Write Activity Metadata
     * 
     * @param   string      $api_name               
     * @param   string      $filter_name            Human-readable name of the associated filter.
     * @param   string      $name                   Human-readable display name of the activity type.
     * @param   array       $primary_attribute      Primary attribute of the activity type.
     * @param   string      $trigger_name           Human-readable name of the associated trigger.
     * @param   string      $description            Optional. Custom activity description.
     * 
     * @return CustomActivity[] | null
     */
    public static function createCustomActivityType($api_name, $filter_name, $name, $primary_attribute, $trigger_name, $description = "" ) {

        $body = [
            'apiName'=>$api_name,
            'filterName'=>$filter_name,
            'name'=>$name,
            'primaryAttribute'=>$primary_attribute,
            'triggerName'=>$trigger_name
        ];

        if( !empty($description) ) $body['description'] = $description;

        return CustomActivity::manufacture(Client::send('POST', 'activities/external/type.json', ['body'=>$body]));
    }

    /**
     * Updates the target custom activity type. All changes are applied to the draft version of the type. 
     * Required Permissions: Read-Write Activity Metadata
     * 
     * @param   string      $api_name               
     * @param   string      $filter_name            Human-readable name of the associated filter.
     * @param   string      $name                   Human-readable display name of the activity type.
     * @param   array       $primary_attribute      Primary attribute of the activity type.
     * @param   string      $trigger_name           Human-readable name of the associated trigger.
     * @param   string      $description            Optional. Custom activity description.
     * 
     * @return CustomActivityType[] | null
     */
    public static function updateCustomActivityType($api_name, $filter_name, $name, $primary_attribute, $trigger_name, $description = "") {
        $body = [
            'apiName'=>$api_name,
            'filterName'=>$filter_name,
            'name'=>$name,
            'primaryAttribute'=>$primary_attribute,
            'triggerName'=>$trigger_name
        ];

        if( !empty($description) ) $body['description'] = $description;

        return CustomActivityType::manufacture(Client::send('POST', 'activities/external/type/' . $api_name . '.json', ['body'=>$body]));
    }

    /**
     * Approves the current draft of the type, and makes it the live version. This will delete the current live version of the type. 
     * Required Permissions: Read-Write Activity Metadata
     * 
     * @param   string      $api_name   
     * 
     * @return CustomActivityType[] | null
     */
    public static function approveCustomActivityType($api_name) {
        return CustomActivityType::manufacture(Client::send('POST', 'activities/external/type/' . $api_name . '/approve.json'));
    }

    /**
     * Adds activity attributes to the target type. These are added to the draft version of the type. 
     * Required Permissions: Read-Write Activity Metadata
     * 
     * @param   string      $api_name
     * @param   array       $custom_activity_type
     * 
     * @return CustomActivityType[] | null
     */
    public static function createCustomActivityTypeAttributes($api_name, $custom_activity_type) {
        return CustomActivityType::manufacture(Client::send('POST', 'activities/external/type/' . $api_name . '/create.json', ['body'=>['attributes'=>$custom_activity_type]]));
    }

    /**
     * Deletes the target attributes from the custom activity type draft. The apiName of each attribute is the primary key for the update. 
     * Required Permissions: Read-Write Activity Metadata
     * 
     * @param   string      $api_name
     * @param   array       $custom_activity_type_attributes
     * 
     * @return CustomActivityType[] | null
     */
    public static function deleteCustomActivityTypeAttributes($api_name, $custom_activity_type_attributes) {
        return CustomActivityType::manufacture(Client::send('POST', 'activities/external/type/' . $api_name . '/attributes/delete.json', 
            ['body'=>['attributes'=>$custom_activity_type_attributes]]));
    }

    /**
     * Updates the attributes of the custom activity type draft. The apiName of each attribute is the primary key for the update. 
     * Required Permissions: Read-Write Activity Metadata
     * 
     * @param   string      $api_name
     * @param   array       $custom_activity_type_attributes
     * 
     * @return CustomActivityType[] | null
     */
    public static function updateCustomActivityTypeAttributes($api_name, $custom_activity_type_attributes) {
        return CustomActivityType::manufacture(Client::send('POST', 'activities/external/type/' . $api_name . '/attributes/update.json', 
            ['body'=>['attributes'=>$custom_activity_type_attributes]]));
    }

    /**
     * Deletes the target custom activity type. The type must first be removed from use by any assets, such as triggers or filters.
     * Required Permissions: Read-Write Activity Metadata
     * 
     * @param   string      $api_name
     * 
     * @return CustomActivityType[] | null
     */
    public static function deleteCustomActivityType($api_name) {
        return CustomActivityType::manufacture(Client::send('POST', 'activities/external/type/' . $api_name . '/delete.json'));
    }

    /**
     * Returns metadata for a specific custom activity type. 
     * Required Permissions: Read-Only Activity Metadata, Read-Write Activity Metadata
     * 
     * @param   string      $api_name
     * @param   boolean     $draft
     * 
     * @return CustomActivityType[] | null
     */
    public static function describeCustomActivityType($api_name, $draft = false) {
        return CustomActivityType::manufacture(Client::send('GET', 'activities/external/type/' . $api_name . '/describe.json', ['query'=>['draft'=>$draft]]));
    }

    /**
     * Discards the current draft of the custom activity type. 
     * Required Permissions: Read-Write Activity Metadata
     * 
     * @param   string      $api_name
     * 
     * @return CustomActivityType[] | null
     */
    public static function discardCustomActivityTypeDraft($api_name) {
        return CustomActivityType::manufacture(Client::send('POST', 'activities/external/type/' . $api_name . '/discardDraft.json'));
    }

    /**
     * Returns metadata regarding custom activities provisioned in the target instance. 
     * Required Permissions: Read-Only Activity Metadata, Read-Write Activity Metadata
     * 
     * @return CustomActivityType[] | null
     */
    public static function getCustomActivityTypes() {
        return CustomActivityType::manufacture(Client::send('GET', 'activities/external/types.json'));
    }

    /**
     * Returns a list of Data Value Changes and New Lead activities after a given datetime. 
     * Required Permissions: Read-Only Activity, Read-Write Activity
     * 
     * @param   string      $next_page_token    Token representation of a datetime returned by the Get Paging Token endpoint. This endpoint will return activities after this datetime.
     * @param   string[]    $fields             Array of field names to return changes for. Field names can be retrieved with the Describe Lead API.
     * @param   int         $list_id            Id of a static list. If set, will only return activities of members of this static list.
     * @param   int[]       $lead_ids           Array of lead ids. If set, will only return activities of the leads with these ids. Allows up to 30 entries.
     * @param   int         $batch_size         Maximum number of records to return. Maximum and default is 300.
     * 
     * @return LeadChange[] | null
     */
    public static function getLeadChanges($next_page_token, $fields, $list_id = null, $lead_ids = [], $batch_size = 300) {
        $query = [
            'nextPageToken'=>$next_page_token,
            'fields'=>$fields,
            'batchSize'=>$batch_size
        ];

        if( !is_null($list_id) ) $query['listId'] = $list_id;
        if( !empty($lead_ids) ) $query['leadIds'] = $lead_ids;

        return LeadChange::manufacture(Client::send('GET', 'activities/leadchanges.json', ['query'=>$query]));
    }

    /**
     * Returns a paging token for use in retrieving activities and data value changes. 
     * Required Permissions: Read-Only Activity, Read-Write Activity
     * 
     * @param   string      $since
     * 
     * @return string A page token
     */
    public static function getPagingToken($since) {
        return Client::send('GET', 'activities/pagingtoken.json', ['query'=>['sinceDatetime'=>$since]])->getNextPageToken();
    }

    /**
     * Returns a list of available activity types in the target instance, along with associated metadata of each type.
     * Required Permissions: Read-Only Activity, Read-Write Activity
     * 
     * @return ActivityType[] | null
     */
    public static function getActivityTypes() {
        return ActivityType::manufacture(Client::send('GET', 'activities/types.json'));
    }

}