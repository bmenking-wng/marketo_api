<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Result;
use WorldNewsGroup\Marketo\Client;

class SalesPerson extends Model {
    public static $fields = [
        'id',
        'reasons',
        'seq',
        'status'
    ];

    /**
     * @internal
     * 
     * Assembles objects based on the Result object
     * 
     * @return SalesPerson[]
     */
    public static function manufacture(Result $result) {
        $objects = [];

        foreach($result->getResults() as $r) {
            $objects[] = new SalesPerson($r);
        }
        
        return $objects;
    }  

    /**
     * Retrieves salesperson records from the destination instance based on the submitted filter. 
     * Required Permissions: Read-Only Sales Person, Read-Write Sales Person
     * 
     * @param   string      $filter_type        The sales person field to filter on. Searchable fields can be retrieved with the Describe Sales Person call.
     * @param   string[]    $filter_values      An array of search values.
     * @param   string[]    $fields             An array of fields to include in the response.
     * @param   int         $batch_size         The batch size to return. The max and default value is 300.
     * @param   string      $next_page_token    A token will be returned by this endpoint if the result set is greater than the batch size 
     *                                          and can be passed in a subsequent call through this parameter. See Paging Tokens for more info.
     * @return SalesPerson[] | null
     */
    public static function getSalesPersons($filter_type, $filter_values, $fields = [], $batch_size = 300, $next_page_token = null) {
        $query = [
            'batchSize'=>$batch_size,
            'filterType'=>$filter_type,
            'filterValues'=>$filter_values
        ];

        if( !empty($fields) ) $query['fields'] = $fields;
        if( !is_null($next_page_token) ) $query['nextPageToken'] = $next_page_token;

        return SalesPerson::manufacture(Client::send('GET', 'rest/v1/salespersons.json', ['query'=>$query]));
    }

    /**
     * Allows inserts, updates, or upserts of salespersons to the target instance. 
     * Required Permissions: Read-Write Sales Person
     * 
     * @param   string      $input      List of input records.
     * @param   string      $action     Type of sync operation to perform = ['createOnly', 'updateOnly', 'createOrUpdate'].
     * @param   string      $dedupe_by  Field to deduplicate on. If the value in the field for a given record is not unique, 
     *                                  an error will be returned for the individual record.
     * @return SalesPerson[] | null
     */
    public static function syncSalesPersons($input, $action = 'createOrUpdate', $dedupe_by = 'email') {
        $body = [
            'input'=>$input,
            'action'=>$action,
            'dedupeBy'=>$dedupe_by
        ];

        return SalesPerson::manufacture(Client::send('POST', 'rest/v1/salespersons.json', ['body'=>$body]));
    }    

    /**
     * Deletes a list of salesperson records from the target instance. Input records should have only one member, based on the value of 'dedupeBy'. 
     * Required Permissions: Read-Write Sales Person
     * 
     * @param   string      $input      List of input records.
     * @param   string      $delete_by  Key to use for deletion of the record ,
     * 
     * @return SalesPerson[] | null
     */
    public static function deleteSalesPersons($input, $delete_by) {
        $body = [
            'input'=>$input,
            'deleteBy'=>$delete_by
        ];

        return SalesPerson::manufacture(Client::send('POST', 'rest/v1/salespersons/delete.json', ['body'=>$body]));
    }     
    
    /**
     * Returns metadata about salespersons and the fields available for interaction via the API. 
     * Required Permissions: Read-Only Sales Person, Read-Write Sales Person
     * 
     * @return ObjectMetaData[] | null
     */
    public static function describeSalesPersons() {
        return ObjectMetaData::manufacture(Client::send('GET', 'rest/v1/salespersons/describe.json'));
    }    
}