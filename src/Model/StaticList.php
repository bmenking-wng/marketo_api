<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Result;
use WorldNewsGroup\Marketo\Client;

class StaticList extends Model {
    public static $fields = [
        'createdAt',
        'id',
        'updatedAt',
        'workspaceId',
        'workspaceName',
        'name'
    ];

    /**
     * @internal
     * 
     * Assembles StaticList objects based on the Result object
     * 
     * @return StaticList[]
     */
    public static function manufacture(Result $result) {
        $objects = [];

        foreach($result->getResults() as $r) {
            $objects[] = new StaticList($r);
        }
        
        return $objects;
    } 
    
    /**
     * Retrieves person records which are members of the given static list. 
     * Required Permissions: Read-Only Lead, Read-Write Lead
     * 
     * @param   int         $list_id            Id of the static list to retrieve records from
     * @param   string[]    $fields             An array of lead fields to return for each record. If unset will return email, 
     *                                          updatedAt, createdAt, lastName, firstName and id
     * @param   int         $batch_size         The batch size to return. The max and default value is 300.
     * @param   string      $next_page_token    A token will be returned by this endpoint if the result set is greater 
     *                                          than the batch size and can be passed in a subsequent call through this parameter. 
     *                                          See Paging Tokens for more info.
     * @return Lead[] | null
     */
    public static function getLeadsByListId($list_id, $fields = [], $batch_size = 300, $next_page_token = null) {
        $query = [
            'batchSize'=>$batch_size
        ];

        if( !empty($fields) ) $query['fields'] = $fields;
        if( !is_null($next_page_token) ) $query['nextPageToken'] = $next_page_token;

        return Lead::manufacture(Client::send('GET', 'rest/v1/list/' . $list_id . '/leads.json', ['query'=>$query]));
    }

    /**
     * Returns a set of static list records based on given filter parameters. 
     * Required Permissions: Read-Only Lead, Read-Write Lead
     * 
     * @param   int[]       $id                         An array of static list ids to return.
     * @param   string[]    $name                       An array of static list names to return.
     * @param   string[]    $program_name               An array of program names. If set will return all static lists that are children of the given programs.
     * @param   string[]    $workspace_name             An array of workspace names. If set will return all static lists that are children of the given workspaces.
     * @param   int         $batch_size                 The batch size to return. The max and default value is 300.
     * @param   string      $next_page_token        	A token will be returned by this endpoint if the result set is greater than the batch size.
     *                                                  and can be passed in a subsequent call through this parameter. See Paging Tokens for more info.
     * @return StaticList[] | null
     */
    public static function getLists($id = [], $name = [], $program_name = [], $workspace_name = [], $batch_size = 300, $next_page_token = null) {
        $query = [
            'batchSize'=>$batch_size
        ];

        if( !empty($id) ) $query['id'] = $id;
        if( !empty($name) ) $query['name'] = $name;
        if( !empty($program_name) ) $query['programName'] = $program_name;
        if( !empty($workspace_name) ) $query['workspaceName'] = $workspace_name;
        if( !is_null($next_page_token) ) $query['nextPageToken'] = $next_page_token;

        return StaticList::manufacture(Client::send('GET', 'rest/v1/lists.json', ['query'=>$query]));
    }

    /**
     * Returns a list record by its id. 
     * Required Permissions: Read-Only Lead, Read-Write Lead
     * 
     * @param   int     $list_id        Id of the static list to retrieve records from
     * 
     * @return StaticList[] | null
     */
    public static function getListById($list_id) {
        return StaticList::manufacture(Client::send('GET', 'rest/v1/lists/' . $list_id . '.json'));
    }

    /**
     * Removes a given set of person records from a target static list. 
     * Required Permissions: Read-Write Lead
     * 
     * @param       int     $list_id
     * @param       int[]   $ids
     * 
     * @return Result | null
     */
    public static function removeFromList($list_id, $ids = []) {
        // TODO: need an object for the ListOperationOutputData returned by this call
        return Client::send('DELETE', 'rest/v1/lists/' . $list_id . '/leads.json', ['query'=>['input'=>$ids]]);
    }

    /**
     * Retrieves person records which are members of the given static list. 
     * Required Permissions: Read-Only Lead, Read-Write Lead
     * 
     * @param       int     $list_id    
     * @param       int[]   $ids
     * 
     * @return Result | null
     */
    public static function addToList($list_id, $ids = []) {
        // TODO: need an object for the ListOperationOutputData returned by this call
        return Client::send('POST', 'rest/v1/lists/' . $list_id . '/leads.json', ['query'=>['input'=>$ids]]);
    }

    /**
     * Checks if leads are members of a given static list. 
     * Required Permissions: Read-Write Lead
     *   
     * @param       int     $list_id    
     * @param       int[]   $ids
     * 
     * @return Result | null
     */
    public static function memberOfList($list_id, $ids = []) {
        // TODO: need an object for the ListOperationOutputData returned by this call
        return Client::send('POST', 'rest/v1/lists/' . $list_id . '/leads/ismember.json', ['query'=>['input'=>$ids]]);
    }
}