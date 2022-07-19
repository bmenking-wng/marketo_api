<?php

namespace WorldNewsGroup\Marketo;

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
