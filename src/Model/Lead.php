<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Client;

class Lead extends Model {
    public static $fields = [
        'id',
        'firstName',
        'lastName',
        'email',
        'updatedAt',
        'createdAt'
    ];

    private $values;

    public function __constuct($import) {
        $this->values = $import;
    }

    public static function getLeadById($leadId, $fields = []) { 
        return Client::send('GET', "lead/$leadId.json", ['query'=>['fields'=>$fields], 'body'=>[]]);
    }

    public static function getLeadsByFilterType($filterType, $filterValues, $fields = [], $batchSize = 300, $nextPageToken = null) {
        if( $batchSize > 300 ) {
            throw new Exception("getLeadsByFilterType: batchSize cannot exceed 300.");
        }

        $query = [
            'filterType'=>$filterType,
            'filterValues'=>$filterValues,
            'fields'=>$fields,
            'batchSize'=>$batchSize,
            'nextPageToken'=>$nextPageToken
        ];

        return Client::send('GET', "leads.json", ['query'=>$query]);
    }

    public static function describeLead() {
        return Client::send('GET', 'leads/describe.json');
    }

    public static function describeLead2() {
        return Client::send('GET', 'leads/describe2.json');
    }

    public function __get($name) {
        if( isset($this->values[$name])) 
            return $this->values[$name];
        else
            return null;
    }
}