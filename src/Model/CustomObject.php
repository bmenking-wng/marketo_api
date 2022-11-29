<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Result;
use WorldNewsGroup\Marketo\Client;

class CustomObject extends Model {
    public static $fields = [
        'marktoGUID',
        'reasons',
        'seq'
    ];

    /**
     * @internal
     * 
     * Assembles objects based on the Result object
     * 
     * @return CustomObject[]
     */
    public static function manufacture(Result $result) {
        $objects = [];

        foreach($result->getResults() as $r) {
            $objects[] = new CustomObject($r);
        }
        
        return $objects;
    } 

    /**
     * Returns a list of Custom Object types available in the target instance, along with id and deduplication information for each type. 
     * Required Permissions: Read-Only Custom Object, Read-Write Custom Object
     * 
     * @param   string[]    $names      An array of names to filter types on
     * 
     * @return ObjectMetaData[] | null
     */
    public static function listCustomObjects($names = []) {
        $query = [];

        if( !empty($names) ) {
            $query = [
                'query'=>[
                    'names'=>$names
                ]
            ];
        }

        return ObjectMetaData::manufacture(Client::send('GET', 'rest/v1/customobjects.json', $query));
    }

    /**
     * Retrieves a list of custom objects records based on filter and set of values. There are two unique types of requests 
     * for this endpoint: one is executed normally using a GET with URL parameters, the other is by passing a JSON object 
     * in the body of a POST and specifying _method=GET in the querystring. The latter is used when dedupeFields attribute 
     * has more than one field, which is known as a "compound key". 
     * Required Permissions: Read-Only Custom Object, Read-Write Custom Object
     * 
     * @param   CustomObject[]      $input          Search values when using a compound key. Each element must include each 
     *                                              of the fields in the compound key. Compound keys are determined by the 
     *                                              contents of "dedupeFields" in the Describe result for the object.
     * @param   array               $fields         An array of fields to return. If not specified, will return the following 
     *                                              fields: marketoGuid, dedupeFields, updatedAt, createdAt, filterType.
     * @param   string              $filter_type    Field to search on. Valid values are: dedupeFields, idFields, and any field 
     *                                              defined in searchableFields attribute of Describe endpoint. Default is dedupeFields.
     * @param   int                 $batch_size     Maximum number of records to return in the response. Max and default is 300.
     * @param   string              $next_page_token    Paging token returned from a previous response.
     * 
     * @deprecated
     * 
     * @return CustomObject[] | null
     */
    public static function getCustomObjects($custom_object_name, $fields = [], $filter_type = null, $batch_size = 300, $next_page_token = null) {      
        $body = [
            'batchSize'=>$batch_size,
            'input'=>$custom_object_name
        ];

        if( !is_null($next_page_token) ) $body['nextPageToken'] = $next_page_token;
        if( !empty($fields) ) $body['fields'] = $fields;
        if( !is_null($filter_type) ) $body['filterType'] = $filter_type;

        return CustomObject::manufacture(Client::send('GET', 'rest/v1/customobjects/' . $custom_object_name . '.json', $body));
    }

    /**
     * Returns a list of Custom Object Types available in the target instance, along with id, deduplication, relationship, and 
     * field information for each type. 
     * Required Permissions: Read-Only Custom Object Type, Read-Write Custom Object Type
     * 
     * @param   string[]    $names      An array of API names of custom object types to filter on.
     * @param   string      $state      State of custom object type to filter on. By default, if an approved version exists, it is returned. 
     *                                  Otherwise, the draft version is returned.  One of: draft, approved or approvedWithDraft (default).
     * 
     * @return ObjectMetaData[] | null
     */
    public static function listCustomObjectTypes($names = [], $state = 'approvedWithDraft') {
        $query = [
            'state'=>$state
        ];

        if( !empty($names) ) $query['names'] = $names;
        
        return ObjectMetaData::manufacture(Client::send('GET', 'rest/v1/customobjects/schema.json', ['query'=>$query]));
    }

    /**
     * 
     */
    public static function syncCustomObjects($custom_object_name, $records = [], $action = 'createOrUpdate', $dedupe_by = null) {
        $body = [
            'action'=>$action,
            'input'=>$records
        ];

        if( !is_null($dedupe_by) ) $body['dedupeBy'] = $dedupe_by;

        return CustomObject::manufacture(Client::send('POST', 'rest/v1/customobjects/' . $custom_object_name . '.json', $body));
    }    
}