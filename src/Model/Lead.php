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

    public function __construct($import) {
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

    /**
     * describeLead
     * 
     * Returns metadata about lead objects in the target instance, including a list of all fields available for interaction via the APIs.
     * Note: This endpoint has been superceded. Use Describe Lead2 endpoint instead.
     * 
     * @return \WorldNewsGroup\Marketo\Model\Lead | null
     */
    public static function describeLead() {
        return Client::send('GET', 'leads/describe.json');
    }

    /**
     * describeLead2
     * 
     * Returns list of searchable fields on lead objects in the target instance.
     * 
     * @return \WorldNewsGroup\Marketo\Model\Lead | null
     */
    public static function describeLead2() {
        return Client::send('GET', 'leads/describe2.json');
    }

    /**
     * getLeadFieldByName
     * 
     * Retrieves metadata for single lead field.
     * 
     * @return \WOrldNewsGroup\Marketo\Model\LeadField | null
     */
    public static function getLeadFieldByName($fieldApiName) {
        return Client::send('GET', 'leads/schema/fields/' . $fieldApiName . '.json');
    }

    /**
     * getLeadFields
     * 
     * Retrieves metadata for all lead fields in the target instance. 
     * Required Permissions: Read-Write Schema Standard Field, Read-Write Schema Custom Field
     * 
     * @return array \WorldNewsGroup\Marketo\Model\LeadField
     */
    public static function getLeadFields($batchSize = 300, $nextPageToken = null) {
        if( $batchSize > 300 ) throw new Exception("Batch size cannod exceed 300 for getLeadFields");

        $params['query'] = ['batchSize'=>$batchSize];

        if( !is_null($nextPageToken) ) {
            $params['query']['nextPageToken'] = $nextPageToken;
        }

        return Client::send('GET', 'leads/schema/fields.json', $params);
    }

    /**
     * getLeadParitions
     * 
     * Returns a list of available partitions in the target instance. 
     * Required Permissions: Read-Only Lead, Read-Write Lead
     * 
     * @return array \WorldNewsGroup\Marketo\Model\LeadPartition
     */
    public static function getLeadPartitions() {
        return Client::send('GET', 'leads/partition.json');
    }

    public static function updateLeadField($fieldApiName, $updateLeadFieldRequest = []) {
        return Client::send('POST', 'leads/schema/fields/' . $fieldApiName . '.json', ['body'=>['updateLeadFieldRequest']]);
    }

    public static function deleteLeads($deleteLeadRequest = []) {
        if( is_empty($deleteLeadRequest) ) return null;

        return Client::send('POST', 'leads/delete.json', ['body'=>['deleteLeadRequest'=>$leadIds]]);
    }
}