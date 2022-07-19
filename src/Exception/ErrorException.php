<?php

namespace WorldNewsGroup\Marketo\Exception;

class ErrorException extends ApiException {
    private $errors;

    public function __construct($errors) {
        parent::__construct("The API request returned an error. " . print_r($errors, true));

        $this->errors = $errors;
    }

    public function getErrors() {
        return $this->errors;
    }
}