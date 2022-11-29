<?php

namespace WorldNewsGroup\Marketo;

use WorldNewsGroup\Marketo\Model\Lead;
use WorldNewsGroup\Marketo\Model\LeadPartition;
use WorldNewsGroup\Marketo\Model\LeadField;

class Result {
    private Array $response;

    /**
     * @internal
     * 
     * @param   mixed[]     $response
     */
    public function __construct($response) {
        $this->response = $response;
    }

    /**
     * getRequestId
     * 
     * Returns the Marketo API request ID associated with this result
     * 
     * @return string
     */
    public function getRequestId() {
        return $this->response['requestId'];
    }

    /**
     * getNextPageToken
     * 
     * If the call has more results (see getMoreResult()) use this page token on the next request.
     * 
     * @return string
     */
    public function getNextPageToken() {
        return $this->response['nextPageToken'];
    }

    /**
     * getMoreResult
     * 
     * Determines if the request can be called again with page token to receive more results.
     * 
     * @return boolean
     */
    public function getMoreResult() {
        return ($this->response['moreResult'] == 1);
    }

    /**
     * getSuccess
     * 
     * Determine if the call was a success.
     * 
     * @return boolean
     */
    public function getSuccess() {
        return ($this->response['success'] == 1);
    }

    /**
     * getResults
     * 
     * Get unprocessed results from request
     * 
     * @return mixed[]
     */
    public function getResults() {
        if( !isset($this->response['result']) ) return [];
        
        return $this->response['result'];
    }
}
