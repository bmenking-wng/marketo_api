<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Result;
use WorldNewsGroup\Marketo\Client;

class SmartList extends Model {
    public static $fields = [

    ];

    /**
     * @internal
     * 
     * Assembles StaticList objects based on the Result object
     * 
     * @return SmartList[]
     */
    public static function manufacture(Result $result) {
        $objects = [];

        foreach($result->getResults() as $r) {
            $objects[] = new SmartList($r);
        }
        
        return $objects;
    } 

    /**
     * Retrieves a Smart List record by its id. 
     * Required Permissions: Read-Asset or Read-Write Asset
     * 
     * @param   int     $smartlist_id       	Id of the smart list to retrieve
     * @param   boolean $include_rules          Set true to populate smart list rules. Default false
     * 
     * @return SmartList[] | null
     */
    public static function getSmartListById(int $smartlist_id, $include_rules = false) {
        $query = [
            'includeRules'=>$include_rules
        ];

        return SmartList::manufacture(Client::send('GET', 'rest/asset/v1/smartList/' . $smartlist_id . '.json', ['query'=>$query]));
    }

    /**
     * Retrieves a Smart List record by its name. 
     * Required Permissions: Read-Asset or Read-Write Asset
     * 
     * @param   string     $name       		Name of smart list to retrieve
     * 
     * @return SmartList[] | null
     */
    public static function getSmartListByName(string $name) {
        $query = [
            'name'=>$name
        ];

        return SmartList::manufacture(Client::send('GET', 'rest/asset/v1/smartList/byName.json', ['query'=>$query]));
    }

    /**
     * Deletes the designated Smart List. 
     * Required Permissions: Read-Write Asset
     * 
     * @param   int     $smartlist_id       	Id of the smart list to delete
     * 
     * @return Result | null
     */
    public static function deleteSmartList(int $smartlist_id) {
        return Client::send('POST', 'rest/asset/v1/smartList/' . $smartlist_id . '/delete.json');
    }

    /**
     * Retrieves a list of user created Smart List records. 
     * Required Permissions: Read-Asset or Read-Write Asset
     * 
     * @param   string      $folder      	            JSON representation of parent folder, with members 'id', and 'type' which may be 'Folder' or 'Program'
     * @param   int         $offset                     Integer offset for paging
     * @param   int         $max_return                 Maximum number of smart lists to return. Max 200, default 20.
     * @param   string      $earliest_updated_at    	Exclude smart lists prior to this date. Must be valid ISO-8601 string. See Datetime field type description.
     * @param   string      $latest_updated_at          Exclude smart lists after this date. Must be valid ISO-8601 string. See Datetime field type description.
     * 
     * @return SmartList[] | null
     */
    public static function getSmartLists(string $folder = null, int $offset = 0, int $max_return = 20, string $earliest_updated_at = null, string $latest_updated_at = null) {
        $query = [
            'maxReturn'=>$max_return,
            'offset'=>$offset
        ];

        if( !is_null($folder) ) $query['folder'] = $folder;
        if( !is_null($earliest_updated_at) ) $query['earliestUpdatedAt'] = $earliest_updated_at;
        if( !is_null($latest_updated_at) ) $query['latestUpdatedAt'] = $latest_updated_at;

        return SmartList::manufacture(Client::send('GET', 'rest/asset/v1/smartLists.json', ['query'=>$query]));
    }    

    /**
     * Clones the designated Smart List. 
     * Required Permissions: Read-Write Asset
     * 
     * @param   string     $name       		Name of smart list to retrieve
     * 
     * @return SmartList[] | null
     */
    public static function cloneSmartList(int $smartlist_id, string $name, string $folder, string $description = null) {
        $body = [
            'name'=>$name,
            'folder'=>$folder
        ];

        if( !is_null($description) ) $query['description'] = $description;

        return SmartList::manufacture(Client::send('POST', 'rest/asset/v1/smartList/' . $smartlist_id . '/clone.json', ['body'=>$body]));
    }    
}