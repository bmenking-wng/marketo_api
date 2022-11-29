<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Result;
use WorldNewsGroup\Marketo\Client;

class StaticLists extends Model {
    public static $fields = [
        'createdAt',
        'id',
        'updatedAt',
        'workspaceId',
        'workspaceName',
        'name',
        'leadId'
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
            $objects[] = new StaticLists($r);
        }
        
        return $objects;
    } 

    public static function createStaticList($name, $folder_id, $description = null ){
        $body = [
            'name'=>$name,
            'folder'=>[
                'id'=>$folder_id,
                'type'=>'Folder'
            ]
        ];

        if( !is_null($description) ) $body['description'] = $description;

        //print_r($body);
        $body['folder'] = json_encode($body['folder']);
        
        return StaticLists::manufacture(Client::send('POST', 'rest/asset/v1/staticLists.json', ['form-encode'=>$body]));
    }

    public static function getStaticListById($id) {
        return StaticLists::manufacture(Client::send('GET', 'rest/asset/v1/staticList/' . $id . '.json'));
    }

    public static function updateStaticListMetadata($id, $name = null, $description = null) {
        $body = [];

        if( !is_null($name) ) $body['name'] = $name;
        if( !is_null($description) ) $body['description'] = $description;

        if( empty($body) ) return null;

        return Client::send('POST', 'rest/asset/v1/staticList/' . $id . '.json', ['body'=>$body]);
    }

    public static function getStaticLists($folder = null, $offset = 0, $maxReturn = 20, $earliestUpdatedAt = null, $lastUpdatedAt = null) {
        $query = [];

        if( !is_null($folder) ) $query['folder'] = $folder;
        if( !is_null($offset) ) $query['offset'] = $offset;
        if( !is_null($maxReturn) ) $query['maxReturn'] = $maxReturn;
        if( !is_null($earliestUpdatedAt) ) $query['earliestUpdatedAt'] = $earliestUpdatedAt;
        if( !is_null($lastUpdatedAt) ) $query['lastUpdatedAt'] = $lastUpdatedAt;

        return StaticLists::manufacture(Client::send('GET', 'rest/asset/v1/staticLists.json', ['query'=>$query]));
    }

    public static function getStaticListByName($name) {
        return StaticLists::manufacture(Client::send('GET', 'rest/asset/v1/staticList/byName.json', ['query'=>['name'=>$name]]));
    }

    public static function deleteStaticList($id) {
        return Client::send('POST', 'rest/asset/v1/staticList/' . $id . '/delete.json');
    }
}