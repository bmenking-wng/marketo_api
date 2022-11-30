<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Result;
use WorldNewsGroup\Marketo\Client;

class Folders extends Model {
    public static $fields = [
        'accessZoneId',
        'createdAt',
        'description',
        'folderId',
        'folderType',
        'id',
        'isArchive',
        'isSystem',
        'name',
        'parent',
        'path',
        'updatedAt',
        'url',
        'workspace'
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

    public static function getFolderByName($name, $type = null, $root = null, $workSpace = null) {
        $query = [
            'name'=>$name
        ];

        if( !is_null($type) ) $query['type'] = $type;
        if( !is_null($root) ) $query['root'] = $root;
        if( !is_null($workSpace) ) $query['workSpace'] = $workSpace;

        return Folders::manufacture(Client::send('GET', 'rest/asset/v1/folder/byName.json', ['query'=>$query]));
    }

    public static function getFolderById($id, $type = 'Folder') {
        $query = [
            'type'=>$type
        ];

        return Folders::manufacture(Client::send('GET', 'rest/asset/v1/folder/' . $id . '.json', ['query'=>$query]));
    }

    public static function updateFolderMetadata($id, $type, $name = null, $isArchive = null, $description = null) {
        $body = [
            'type'=>$type
        ];

        if( !is_null($name) ) $body['name'] = $name;
        if( !is_null($isArchive) ) $body['isArchive'] = $isArchive;
        if( !is_null($description) ) $body['description'] = $description;

        return Client::send('POST', 'rest/asset/v1/folder/' . $id . '.json', ['body'=>$body]);
    }

    public static function getFolderContents($id, $type = 'Folder', $maxReturn = 20, $offset = null) {
        $query = [
            'type'=>$type,
            'maxReturn'=>$maxReturn
        ];

        if( !is_null($offset) ) $query['offset'] = $offset;

        return Client::send('GET', 'rest/asset/v1/folder/' . $id . '/content.json', ['query'=>$query]);
    }

    public static function deleteFolder($id, $type = 'Folder') {
        return Client::send('POST', 'rest/asset/v1/folder/' . $id . '/delete.json', ['body'=>['type'=>$type]]);
    }

    public static function getFolders($root = null, $maxDepth = 2, $maxReturn = 20, $offset = 0, $workSpace = null) {
        $query = [
            'maxDepth'=>$maxDepth,
            'maxReturn'=>$maxReturn,
            'offset'=>$offset
        ];

        if( !is_null($root) ) $query['root'] = $root;
        if( !is_null($workSpace) ) $query['workSpace'] = $workSpace;

        return Folders::manufacture(Client::send('GET', 'rest/asset/v1/folders.json', ['query'=>$query]));
    }

    public static function createFolder($name, $parent = null, $description = null) {
        $body = [
            'name'=>$name
        ];

        if( !is_null($parent) ) $body['parent'] = $parent;
        if( !is_null($description) ) $body['description'] = $description;

        return Client::send('POST', 'rest/asset/v1/folders.json', ['body'=>$body]);
    }
}
