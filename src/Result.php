<?php

namespace WorldNewsGroup\Marketo;

use WorldNewsGroup\Marketo\Model\Lead;
use WorldNewsGroup\Marketo\Model\LeadPartition;
use WorldNewsGroup\Marketo\Model\LeadField;

class Result {
    private $response;

    public function __construct($response) {
        $this->response = $response;
    }

    public function getRequestId() {
        return $this->response['requestId'];
    }

    public function lead() {
        foreach(Lead::$fields as $field) {
            if( !isset($this->response[$field])) {
                return null;
            }
        }

        return new Lead($this->response['result']);
    }

    /**
     * leads
     * 
     * @return array \WorldNewsGroup\Marketo\Model\Lead | null
     */
    public function leads() {
        $response = [];

        foreach($this->response['result'] as $data) {
            if( !isset($data['firstName']) ) return null;

            $response[] = new LeadField($data);
        }

        return $response;
    }

    /**
     * fields
     * 
     * @return array \WorldNewsGroup\Marketo\Model\LeadField | null
     */
    public function fields() {
        $response = [];

        foreach($this->response['result'] as $data) {
            if( !isset($data['displayName']) ) return null;

            $response[] = new LeadField($data);
        }

        return $response;
    }

    /**
     * partitions
     * 
     * @return array \WorldNewsGroup\Marketo\Model\LeadPartition | null
     */
    public function partitions() {
        $response = [];

        foreach($this->response['result'] as $data) {
            if( !isset($data['description']) ) return null;

            $response[] = new LeadPartition($data);
        }

        return $response;        
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
}
