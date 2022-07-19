<?php

namespace WorldNewsGroup\Marketo\Model;

class Model {
    public static $fields;
    
    private $values;

    public function __get($name) {
        if( in_array($name, $this->fields) && isset($fields[$name]) ) {
            return $this->response['result'][$name];
        }
    }

    public function __isset($name) {
        return (in_array($name, $this->fields) && isset($fields[$name]));
    }
}