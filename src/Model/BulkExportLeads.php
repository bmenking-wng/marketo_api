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
     */
    public static function getExportLeadJobs($status = [], $batch_size = 300, $next_page_token = null) {
        $query = [
            'batchSize'=>$batchSize
        ];

        if( !is_null($next_page_token) ) $query['nextPageToken'] = $next_page_token;
        
        // return Client::send('bulk/v1/leads/export.json', )
        return null;
    }
}