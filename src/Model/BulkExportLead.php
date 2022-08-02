<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Result;
use WorldNewsGroup\Marketo\Client;

class BulkExportLead extends Model {
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
     * @return BulkExportLead[]
     */
    public static function manufacture(Result $result) {
        $objects = [];

        foreach($result->getResults() as $r) {
            $objects[] = new BulkExportLead($r);
        }
        
        return $objects;
    }

    /**
     * Returns a list of export jobs that were created in the past 7 days. 
     * Required Permissions: Read-Only Lead
     * 
     * @param   string[]    $status     An array of one or more of: created, queued, processing, cancelled, completed, failed
     * @param   int         $batch_size The batch size to return. The max and default value is 300.
     * @param   string      $next_page_token    A token will be returned by this endpoint if the result set is greater than the 
     *                                          batch size and can be passed in a subsequent call through this parameter. See Paging Tokens for more info.
     * 
     * @return Export[] | null
     */
    public static function getExportLeadJobs($status = [], $batch_size = 300, $next_page_token = null) {
        $query = [
            'batchSize'=>$batch_size
        ];

        if( !is_null($next_page_token) ) $query['nextPageToken'] = $next_page_token;

        return Export::manufacture(Client::send('GET', 'bulk/v1/leads/export.json', ['query'=>$query]));
    }

    /**
     * Create export job for search criteria defined via "filter" parameter. Request returns the "exportId" which is passed as a parameter in 
     * subsequent calls to Bulk Export Leads endpoints. Use Enqueue Export Lead Job endpoint to queue the export job for processing. Use 
     * Get Export Lead Job Status endpoint to retrieve status of export job. 
     * Required Permissions: Read-Only Lead
     * 
     * @param   array       $filter                 Lead record selection criteria. Can be one of the following: "createdAt", "updatedAt", 
     *                                              "staticListName", "staticListId", "smartListName", "smartListId"
     * @param   array       $column_header_names    File header field names override (corresponds with REST API name)
     * @param   array       $fields                 An array of fields to include in the file.
     * @param   string      $format                 File format to create("CSV", "TSV", "SSV"). Default is "CSV"
     * 
     * @return Export[] | null
     */
    public static function createExportLeadJob($filter, $column_header_names = [], $fields = [], $format = "CSV") {
        $body = [
            'filter'=>$filter,
            'format'=>$format
        ];

        if( !empty($column_header_names) ) $body['columnHeaderNames'] = $column_header_names;
        if( !empty($fields) ) $body['fields'] = $fields;

        return Export::manufacture(Client::send('POST', 'bulk/v1/leads/export/create.json', ['body'=>$body]));
    }

    /**
     * Cancel export job. 
     * Required Permissions: Read-Only Lead
     * 
     * @param   string      $export_id      Id of export batch job.
     * 
     * @return Export[] | null
     */
    public static function cancelExportLeadJob($export_id) {
        return Export::manufacture(Client::send('POST', 'bulk/v1/leads/export/' . $export_id . '/cancel.json'));
    }

    /**
     * Enqueue export job. This will place export job in queue, and will start the job when computing resources become 
     * available. The export job must be in "Created" state. Use Get Export Lead Job Status endpoint to retrieve status of export job. 
     * Required Permissions: Read-Only Lead
     * 
     * @param   string      $export_id      Id of export batch job.
     * 
     * @return Export[] | null
     */
    public static function enqueueExportLeadJob($export_id) {
        return Export::manufacture(Client::send('POST', 'bulk/v1/leads/export/' . $export_id . '/enqueue.json'));
    }

    /**
     * Returns the file content of an export job. The export job must be in "Completed" state. Use Get Export Lead Job Status 
     * endpoint to retrieve status of export job. 
     * Required Permissions: Read-Only Lead
     * 
     * @param   string      $export_id      Id of export batch job.
     * @TODO: range
     * 
     * @return string
     */
    public static function getExportLeadFile($export_id) {
        return Client::send('GET', 'bulk/v1/leads/export/' . $export_id . '/file.json');
    }

    /**
     * Returns status of an export job. Job status is available for 30 days after Completed or Failed status was reached. 
     * Required Permissions: Read-Only Lead
     * 
     * @param   string      $export_id      Id of export batch job.
     * 
     * @return Export[] | null
     */
    public static function getExportLeadJobStatus($export_id) {
        return Export::manufacture(Client::send('GET', 'bulk/v1/leads/export/' . $export_id . '/status.json'));
    }
}