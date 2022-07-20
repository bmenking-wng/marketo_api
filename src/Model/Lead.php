<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Result;
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

    /**
     * @internal
     * 
     * Assembles objects based on the Result object
     * 
     * @return array An array of Campaign objects
     */
    public static function manufacture(Result $result) {
        $objects = [];

        foreach($result->getResults() as $r) {
            $objects[] = new Campaign($r);
        }
        
        return $objects;
    }

    /**
     * Retrieves a single lead record through its Marketo id. 
     * Required Permissions: Read-Only Lead, Read-Write Lead
     * 
     * @param   string      $lead_id        ID of lead in Marketo
     * @param   array       $fields         Array of field names.  If omitted, the following default fields will
     *                                      be returned: email, updatedAt, createdAt, lastName, firstName and id.
     * 
     * @return array | null     If array, an array of Lead
     */
    public static function getLeadById($lead_id, $fields = []) { 
        return Lead::manufacture(Client::send('GET', "lead/$lead_id.json", ['query'=>['fields'=>$fields], 'body'=>[]]));
    }

    /**
     * Returns a list of up to 300 leads based on a list of values in a particular field. 
     * Required Permissions: Read-Only Lead, Read-Write Lead
     * 
     * @param   array       $filter_type        The lead field to filter on.  See Marketo docs for more information.
     * @param   array       $filter_values      An array of values to filter on in the specified fields.
     * @param   array       $fields             Optional. An array of lead fields to return for each record.
     * @param   int         $batch_size         Optional. Batch size to return.  Max and default value is 300.
     * @param   string      $next_page_token    Optional. A token will be returned by this endpoint if result is greater than batch size.
     *                                          The token can be passed to subsequent calls through this parameter.
     * 
     * @return array | null If array, an array of Lead  
     */
    public static function getLeadsByFilterType($filter_type, $filter_values, $fields = [], $batch_size = 300, $next_page_token = null) {
        if( $batch_size > 300 ) {
            throw new \Exception("getLeadsByFilterType: batch_size cannot exceed 300.");
        }

        $query = [
            'filterType'=>$filter_type,
            'filterValues'=>$filter_values,
            'fields'=>$fields,
            'batchSize'=>$batch_size,
            'nextPageToken'=>$next_page_token
        ];

        return Lead::manufacture(Client::send('GET', "leads.json", ['query'=>$query]));
    }

    /**
     * Syncs a list of leads to the target instance. 
     * Required Permissions: Read-Write Lead
     * 
     * @param   array       $leads      sync lead request (TODO: need this as an object)
     * 
     * @return array | null If array, an array of Lead.
     */
    public static function syncLeads(Array $leads) {
        return Lead::manufacture(Client::send('POST', 'leads.json', ['body'=>['input'=>$leads]]));
    }

    /**
     * Delete a list of leads from the destination instance. 
     * Required Permissions: Read-Write Lead
     * 
     * @param   array       $leads      delete lead request (TODO: need this as an object)
     * 
     * @return array | null If array, an array of Lead.
     */
    public static function deleteLeads(Array $leads) {
        return Lead::manufacture(Client::send('POST', 'leads/delete.json', ['body'=>['input'=>$leads]]));
    }

    /**
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
     * Upserts a lead and generates a Push Lead to Marketo activity. 
     * Required Permissions: Read-Write Lead
     * 
     * @param array     $lead_data      An array of lead arrays.
     * @param string    $program_name   Program name to assign to each lead.
     * 
     * @return boolean
     */
    public static function pushLeadToMarketo(Array $lead_data, $program_name) {
        $params = [
            'body'=>[
                'lookupField'=>'email',
                'action'=>'createOrUpdate',
                'input'=>$lead_data
            ]
        ];
    
        return Client::send('POST', 'leads/push.json', $params);
    }

}