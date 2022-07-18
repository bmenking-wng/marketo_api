<?php

namespace WorldNewsGroup\Marketo;

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
}