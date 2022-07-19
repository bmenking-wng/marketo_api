<?php

namespace WorldNewsGroup\Marketo;

use WorldNewsGroup\Marketo\Model\LeadField;

class ListResult {
    private $response;

    public function __construct($response) {
        $this->response = $response;
    }

    public function getRequestId() {
        return $this->response['requestId'];
    }

    public function leads() {

    }

    public function fields() {
        $response = [];

        foreach($this->response['result'] as $data) {
            // if displayName doesn't exist we probably don't have a LeadField array
            if( !isset($data['displayName']) ) return null;

            $response[] = new LeadField($data);
        }

        return $response;
    }

    public function getNextPageToken() {
        return $this->response['nextPageToken'];
    }

    public function getMoreResult() {
        return $this->response['moreResult'];
    }

    public function getSuccess() {
        return $this->response['success'];
    }
}