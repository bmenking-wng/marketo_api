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
            throw new \Exception("getLeadsByFilterType: batchSize cannot exceed 300.");
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
     * @throws \WorldNewsGroup\Marketo\Exception\ErrorException
     * @return \WorldNewsGroup\Marketo\Model\Lead | null | \WorldNewsGroup\Marketo\Exception\ErrorException
     */
    public static function describeLead() {
        return Client::send('GET', 'leads/describe.json');
    }

    /**
     * describeLead2
     * 
     * Returns list of searchable fields on lead objects in the target instance.
     * 
     * @throws \WorldNewsGroup\Marketo\Exception\ErrorException
     * @return \WorldNewsGroup\Marketo\Model\Lead | null | \WorldNewsGroup\Marketo\Exception\ErrorException
     */
    public static function describeLead2() {
        return Client::send('GET', 'leads/describe2.json');
    }

    /**
     * getLeadFieldByName
     * 
     * Retrieves metadata for single lead field.
     * @throws \WorldNewsGroup\Marketo\Exception\ErrorException
     * @return \WorldNewsGroup\Marketo\Model\LeadField | null | \WorldNewsGroup\Marketo\Exception\ErrorException
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
     * @return array
     */
    public static function getLeadFields($batchSize = 300, $nextPageToken = null) {
        if( $batchSize > 300 ) throw new \Exception("Batch size cannod exceed 300 for getLeadFields");

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
     * @throws \WorldNewsGroup\Marketo\Exception\ErrorException
     * @return array | \WorldNewsGroup\Marketo\Exception\ErrorException
     */
    public static function getLeadPartitions() {
        return Client::send('GET', 'leads/partitions.json');
    }

    /**
     * pushLeadToMarketo
     * 
     * Upserts a lead and generates a Push Lead to Marketo activity. 
     * Required Permissions: Read-Write Lead
     * 
     * @return boolean
     */
    public static function pushLeadToMarketo(Lead $lead, $program_name) {
        $params = [
            'body'=>[
                'lookupField'=>'email',
                'action'=>'createOrUpdate',
                'input'=>[
                    [
                        'firstName'=>$lead->firstName,
                        'lastName'=>$lead->lastName,
                        'email'=>$lead->email     
                    ]           
                ]
            ]
        ];
    
        return Client::send('POST', 'leads/push.json', $params);
    }

    /**
     * syncLeads
     * 
     * Syncs a list of leads to the target instance. 
     * Required Permissions: Read-Write Lead
     * 
     * @return array
     */
    public static function syncLeads(Array $leads) {
        return Client::send('POST', 'leads.json', ['body'=>['input'=>$leads]]);
    }

    /**
     * deleteLeads
     * 
     * Delete a list of leads from the destination instance. 
     * Required Permissions: Read-Write Lead
     * 
     * @return boolean
     */
    public static function deleteLeads(Array $leads) {
        return Client::send('POST', 'leads/delete.json', ['body'=>['input'=>$leads]]);
    }

}