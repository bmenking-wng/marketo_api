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
        'createdAt',
        'status'
    ];

    /**
     * @internal
     * 
     * Assembles objects based on the Result object
     * 
     * @return Lead[]
     */
    public static function manufacture(Result $result) {
        $objects = [];

        foreach($result->getResults() as $r) {
            $objects[] = new Lead($r);
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
     * @return Lead[] | null
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
     * @return Lead[] | null
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
     * @param   array       $leads          sync lead request (TODO: need this as an object)
     * @param   string      $action         Type of sync operation to perform. Defaults to createOrUpdate 
     *                                      if unset = ['createOnly', 'updateOnly', 'createOrUpdate', 'createDuplicate'].
     * @param   string      $lookup_field   Field to deduplicate on. The field must be present in each lead record of the input. Defaults to email if unset.
     * @param   string      $partition_name Name of the partition to operate on, if applicable. Should be set whenever 
     *                                      possible, when interacting with an instance where partitions are enabled.
     * @param   boolean     $async_processing   If set to true, the call will return immediately ,
     * 
     * @return Lead[] | null
     */
    public static function syncLeads(Array $leads, $action = "createOrUpdate", $lookup_field = "email", $partition_name = "", $async_processing = false) {
        $body = [
            'input'=>$leads,
            'action'=>$action,
            'lookupField'=>$lookup_field,
            'asyncProcessing'=>$async_processing
        ];

        if( !empty($partition_name) ) $body['partitionName'] = $partition_name;
        
        return Lead::manufacture(Client::send('POST', 'leads.json', ['body'=>$body]));
    }

    /**
     * Delete a list of leads from the destination instance. 
     * Required Permissions: Read-Write Lead
     * 
     * @param   array       $leads      delete lead request (TODO: need this as an object)
     * 
     * @return Lead[] | null
     */
    public static function deleteLeads(Array $leads) {
        return Lead::manufacture(Client::send('POST', 'leads/delete.json', ['body'=>['input'=>$leads]]));
    }

    /**
     * Returns metadata about lead objects in the target instance, including a list of all fields available for interaction via the APIs.
     * Note: This endpoint has been superceded. Use Describe Lead2 endpoint instead.
     * @deprecated
     * 
     * @return LeadAttribute[] | null
     */
    public static function describeLead() {
        return LeadAttribute::manufacture(Client::send('GET', 'leads/describe.json'));
    }

    /**
     * Returns list of searchable fields on lead objects in the target instance.
     * 
     * @return LeadAttribute2[] | null
     */
    public static function describeLead2() {
        return LeadAttribute2::manufacture(Client::send('GET', 'leads/describe2.json'));
    }

    /**
     * Retrieves metadata for single lead field.
     * 
     * @param       string      $field_api_name     The API name of the lead field.
     * 
     * @return LeadField[] | null
     */
    public static function getLeadFieldByName(String $field_api_name) {
        return LeadField::manufacture(Client::send('GET', 'leads/schema/fields/' . $field_api_name . '.json'));
    }

    /**
     * Update metadata for a lead field in the target instance. See update rules here. 
     * Required Permissions: Read-Write Schema Standard Field, Read-Write Schema Custom Field
     * 
     * @param       string      $field_api_name             The API name of the lead field.
     * @param       array       $update_lead_field_request  See Marketo API documentation (TODO: this needs to be an object)
     * 
     * @return LeadFieldStatus[] | null
     */
    public static function updateLeadField(String $field_api_name, $update_lead_field_request) {
        return LeadFieldStatus::manufacture(Client::send('POST', 'leads/schema/fields/' . $field_api_name . '.json', ['body'=>['input'=>$update_lead_field_request]]));
    }

    /**
     * Retrieves metadata for all lead fields in the target instance. 
     * Required Permissions: Read-Write Schema Standard Field, Read-Write Schema Custom Field
     * 
     * @param   int         $batch_size         The batch size to return.  Max and default size is 300.
     * @param   string      $next_page_token    The token returned from a previous call signifying more results are available using this token.
     * 
     * @return LeadField[] | null
     */
    public static function getLeadFields($batch_size = 300, $next_page_token = null) {
        if( $batch_size > 300 ) throw new \Exception("Batch size cannod exceed 300 for getLeadFields");

        $params['query'] = ['batchSize'=>$batch_size];

        if( !is_null($next_page_token) ) {
            $params['query']['nextPageToken'] = $next_page_token;
        }

        return LeadField::manufacture(Client::send('GET', 'leads/schema/fields.json', $params));
    }

    /**
     * Create lead fields in the target instance. 
     * Required Permissions: Read-Write Schema Custom Field
     * 
     * @param   array       $create_lead_field      List of lead fields
     * 
     * @return LeadFieldStatus[] | null
     */
    public static function createLeadFields($create_lead_field) {
        return LeadFieldStatus::manufacture(Client::send('POST', 'leads/schema/fields.json', ['body'=>['input'=>$create_lead_field]]));
    }

    /**
     * Returns metadata about program member objects in the target instance, including a list of all fields available for interaction via the APIs. 
     * Required Permissions: Read-Only Lead, Read-Write Lead
     * 
     * @deprecated
     * 
     * @return null
     */
    public static function describeProgramMember() {
        //return ProgramMemberAttribute::manufacture(Client::send('GET', 'program/members/describe.json'));
        return null;
    }

    /**
     * Returns a list of available partitions in the target instance. 
     * Required Permissions: Read-Only Lead, Read-Write Lead
     * 
     * @return LeadPartition[] | null
     */
    public static function getLeadPartitions() {
        return LeadPartition::manufacture(Client::send('GET', 'leads/partitions.json'));
    }

    /**
     * Updates the lead partition for a list of leads. 
     * Required Permissions: Read-Write Lead
     * 
     * @param   array   $update_lead_partition  List of leads
     * @return Lead[] | null
     */
    public static function updateLeadPartition($update_lead_partition) {
        return Lead::manufacture(Client::send('POST', 'leads/partitions.json', ['body'=>['input'=>$update_lead_partition]]));
    }

    /**
     * Retrieves a list of leads which are members of the designated program. 
     * Required Permissions: Read-Only Lead, Read-Write Lead
     * 
     * @param   string      $program_id         The id of the program to retrieve from
     * @param   array       $fields             An array of fields to be returned for each record
     * @param   int         $batch_size         Batch size to return.  Max and default is 300.
     * @param   string      $next_page_token    A token will be returned by this endpoint if the result set is greater 
     *                                          than the batch size and can be passed in a subsequent call through this parameter.
     * @return Lead[] | null
     */
    public static function getLeadsByProgramId($program_id, $fields = [], $batch_size = 300, $next_page_token = "") {
        $query = [
            'batchSize'=>$batch_size
        ];

        if( !empty($fields) ) $body['fields'] = $fields;
        if( !empty($next_page_token) ) $body['nextPageToken'] = $next_page_token;

        return Lead::manufacture(Client::send('GET', 'leads/programs/' . $program_id . '.json', ['query'=>$query]));
    }

    /**
     * Changes the program status of a list of leads in a target program. Only existing members of the program may have their status changed with this API. 
     * Required Permissions: Read-Write Lead
     * 
     * @param   string      $program_id                     The id of the target program
     * @param   array       $change_lead_program_status     List of lead and statuses
     * 
     * @return ChangeLeadProgramStatus[] | null
     */
    public static function changeLeadProgramStatus($program_id, $change_lead_program_status, $status = "") {
        $body = [
            'input'=>$change_lead_program_status
        ];

        if( !empty($status) ) $body['status'] = $status;

        return ChangeLeadProgramStatus::manufacture(Client::send('POST', 'leads/programs/' . $program_id . '/status.json', ['body'=>$body]));
    }

    /**
     * Upserts a lead and generates a Push Lead to Marketo activity. 
     * Required Permissions: Read-Write Lead
     * 
     * @param   array       $lead_data        An array of lead arrays.
     * @param   string      $program_name     Program name to assign to each lead.
     * @param   string      $lookup_field     One of TODO: finish documentation
     * @param   string      $action           The action to take.  Defaults to 'createOrUpdate'
     * 
     * @return Lead[] | null
     */
    public static function pushLeadToMarketo(Array $lead_data, $program_name = "", $lookup_field = 'email', $action = 'createOrUpdate') {
        $params = [
            'body'=>[
                'lookupField'=>$lookup_field,
                'action'=>$action,
                'input'=>$lead_data
            ]
        ];

        if( !empty($program_name) ) $params['body']['programName'] = $program_name;
    
        return Lead::manufacture(Client::send('POST', 'leads/push.json', $params));
    }

    /**
     * Upserts a lead and generates a "Fill out Form" activity which is associated back to program and/or campaign. 
     * Required Permissions: Read-Write Lead
     * 
     * @param   array       $form_fields            Contains form fields and visitor data to use
     * @param   int         $form_id                ID of the form
     * @param   int         $program_id             Id of the program to add lead and/or program member custom fields to.
     * 
     * @return Form[] | null
     */
    public static function submitForm($form_fields, $form_id, $program_id = null) {
        $body = [
            'input'=>$form_fields,
            'formId'=>$form_id
        ];

        if( !is_null($program_id) ) $body['programId'] = $program_id;

        return Form::manufacture(Client::send('POST', 'leads/submitForm.json', ['body'=>$body]));
    }

    /**
     * Associates a known Marketo lead record to a munchkin cookie and its associated web acitvity history. 
     * Required Permissions: Read-Write Lead
     * 
     * @param   int       $lead_id     The id of the lead to associate
     * @param   string     $cookie      The cookie value to associate
     * 
     * @return null
     */
    public static function associateLead($lead_id, $cookie) {
        Client::send('POST', 'leads/' . $lead_id . '/associate.json', ['query'=>['cookie'=>$cookie]]);
        return null;
    }

    /**
     * Merges two or more known lead records into a single lead record. 
     * Required Permissions: Read-Write Lead
     * 
     * @param   int    $master_lead_id     The id of the winning lead record
     * @param   int    $slave_lead_id      The id of the losing record
     * @param   array   $lead_ids           An array of ids of losing records
     * @param   boolean $merge_in_crm       If true, will attempt to merge the designated records in a 
     *                                      natively-synched CRM. Only valid for instances with are natively synched to SFDC.
     * @return null
     */
    public static function mergeLeads($master_lead_id, $slave_lead_id = null, $lead_ids = [], $merge_in_crm = false) {
        $query = [
            'mergeInCRM'=>$merge_in_crm
        ];

        if( !empty($slave_lead_id) ) $query['leadId'] = $slave_lead_id;
        if( !empty($lead_ids) ) $query['leadIds'] = $lead_ids;
    
        Client::send('POST', 'leads/' . $master_lead_id . '/merge.json', ['query'=>$query]);
        return null;
    }

    /**
     * Query static list membership for one lead. 
     * Required Permissions: Read-Only Asset
     * 
     * @param   int    $lead_id    The Marketo lead ID
     * 
     * @return StaticList[] | null
     */
    public static function getListsByLeadId($lead_id, $batch_size = 300, $next_page_token = "") {
        $query = [
            'batchSize'=>$batch_size
        ];

        if( !empty($next_page_token) ) $query['nextPageToken'] = $next_page_token;

        return StaticList::manufacture(Client::send('GET', 'leads/' . $lead_id . '/listMembership.json', ['query'=>$query]));
    }

    /**
     * Query program membership for one lead. 
     * Required Permissions: Read-Only Asset
     * 
     * @param   int    $lead_id                The Marketo lead ID
     * @param   string  $earliest_updated_at    Exclude programs prior to this date. Must be valid ISO-8601 string. See Datetime field type description.
     * @param   string  $latest_updated_at       Exclude programs after this date. Must be valid ISO-8601 string. See Datetime field type description.
     * @param   string  $filter_type            Set to "programId" to filter a set of programs.
     * @param   array   $filter_values          An array of program ids to match against
     * 
     * @return Program[] | null
     */
    public static function getProgramsByLeadId($lead_id, $earliest_updated_at = "", $latest_updated_at = "", $filter_type = "", $filter_values = [], $batch_size = 300, $next_page_token = "") {
        $query = [
            'batchSize'=>$batch_size
        ];

        if( !empty($earliest_updated_at) ) $query['earliestUpdatedAt'] = $earliest_updated_at;
        if( !empty($latest_updated_at) ) $query['latestUpdatedAt'] = $latest_updated_at;
        if( !empty($filter_type) ) $query['filterType'] = $filter_type;
        if( !empty($filter_values) ) $query['filterValues'] = $filter_values;
        if( !empty($next_page_token) ) $query['nextPageToken'] = $next_page_token;

        return Program::manufacture(Client::send('GET', 'leads/' . $lead_id . '/programMembership.json', ['query'=>$query]));
    }    

    /**
     * Query smart campaign membership for one lead. 
     * Required Permissions: Read-Only Asset
     * 
     * @param   int    $lead_id                The Marketo lead ID
     * @param   string  $earliest_updated_at    Exclude smart campaigns prior to this date. Must be valid ISO-8601 string. See Datetime field type description.
     * @param   string  $latest_updated_at       Exclude smart campaigns after this date. Must be valid ISO-8601 string. See Datetime field type description.
     * @param   int     $batch_size             Maximum number of records to return. Maximum and default is 300.
     * @param   string  $next_page_token        A token will be returned by this endpoint if the result set is greater than the batch size and can be passed in 
     *                                          a subsequent call through this parameter. See Paging Tokens for more info.
     * 
     * @return SmartCampaign[] | null
     */
    public static function getSmartCampaignsByLeadId($lead_id, $earliest_updated_at = "", $latest_updated_at = "", $batch_size = 300, $next_page_token = "") {
        $query = [
            'batchSize'=>$batch_size
        ];

        if( !empty($earliest_updated_at) ) $query['earliestUpdatedAt'] = $earliest_updated_at;
        if( !empty($latest_updated_at) ) $query['latestUpdatedAt'] = $latest_updated_at;
        if( !empty($next_page_token) ) $query['nextPageToken'] = $next_page_token;

        return SmartCampaign::manufacture(Client::send('GET', 'leads/' . $lead_id . '/programMembership.json', ['query'=>$query]));
    }      
}