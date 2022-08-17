<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Result;
use WorldNewsGroup\Marketo\Client;

class BulkExportProgramMember extends Model {
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
     * @return BulkExportProgramMember[]
     */
    public static function manufacture(Result $result) {
        $objects = [];

        foreach($result->getResults() as $r) {
            $objects[] = new BulkExportProgramMember($r);
        }
        
        return $objects;
    }

    /**
     * Returns a list of export jobs that were created in the past 7 days. 
     * Required Permissions: Read-Only Lead
     * 
     * @param   string[]    $status     Comma separated list of statuses to filter on.
     * @param   int         $batch_size The batch size to return. The max and default value is 300.
     * @param   string      $next_page_token     A token will be returned by this endpoint if the result set is greater than 
     *                                           the batch size and can be passed in a subsequent call through this parameter. 
     *                                           See Paging Tokens for more info.
     * @return Export[] | null
     */
    public static function getExportProgramMemberJobs(Array $status, int $batch_size = 300, String $next_page_token = null) {
        $query = [
            'batchSize'=>$batch_size,
            'status'=>$status
        ];

        if( !is_null($next_page_token) ) $query['nextPageToken'] = $next_page_token;

        return Export::manufacture(Client::send('GET', 'bulk/v1/program/members/export.json', ['query'=>$query]));
    }

    /**
     * Create export job for search criteria defined via "filter" parameter. Request returns the "exportId" which is passed as a 
     * parameter in subsequent calls to Bulk Export Program Members endpoints. Use Enqueue Export Program Member Job endpoint to 
     * queue the export job for processing. Use Get Export Program Member Job Status endpoint to retrieve status of export job. 
     * Required Permissions: Read-Only Lead
     * 
     * 
     * 
     */
    public static function createExportProgramMemberJob(int $program_id, Array $fields, Array $column_header_names = null, String $format = 'CSV') {
        $body = [
            'filter'=>[
                'programId'=>$program_id
            ],
            'format'=>$format,
            'fields'=>$fields
        ];

        if( !is_null($column_header_names) ) $body['columnHeaderNames'] = $column_header_names;

        return Export::manufacture(Client::send('POST', 'bulk/v1/program/members/export/create.json', ['body'=>$body]));
    }

    /**
     * Cancel export job. 
     * Required Permissions: Read-Only Lead
     * 
     * @return Export[] | null
     */
    public static function cancelExportProgramMemberJob($export_id) {
        return Export::manufacture(Client::send('POST', 'bulk/v1/program/members/export/' . $export_id . '/cancel.json'));
    }

    /**
     * Enqueue export job. This will place export job in queue, and will start the job when computing resources become available. 
     * The export job must be in "Created" state. Use Get Export Program Member Job Status endpoint to retrieve status of export job. 
     * Required Permissions: Read-Only Lead
     * 
     * @return Export[] | null
     */
    public static function enqueueExportProgramMemberJob($export_id) {
        return Export::manufacture(Client::send('POST', 'bulk/v1/program/members/export/' . $export_id . '/enqueue.json'));
    }

    /**
     * Returns the file content of an export job. The export job must be in "Completed" state. Use Get Export Program Member Job 
     * Status endpoint to retrieve status of export job. 
     * Required Permissions: Read-Only Lead
     * The file format is specified by calling the Create Export Program Member Job endpoint. The following is an example of the default file format ("CSV").
     * 
     * @return String
     */
    public static function getExportProgramMemberFile($export_id) {
        return Client::send('GET', 'bulk/v1/program/members/export/' . $export_id . '/file.json', [], null, [], true);
    }

    /**
     * Returns status of an export job. Job status is available for 30 days after Completed or Failed status was reached. 
     * Required Permissions: Read-Only Lead
     * 
     * @return Export[] | null
     */
    public static function getExportProgramMemberJobStatus($export_id) {
        return Export::manufacture(Client::send('GET', 'bulk/v1/program/members/export/' . $export_id . '/status.json'));
    }
}
