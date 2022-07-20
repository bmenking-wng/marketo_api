<?php

namespace WorldNewsGroup\Marketo\Model;

class Model {
    public static $fields = [];
    
    protected $values;

    public function __get($name) {
        if( in_array($name, self::$fields) && isset(self::$fields[$name]) ) {
            return $this->values['result'][$name];
        }
    }

    public function __isset($name) {
        return (in_array($name, self::$fields) && isset(self::$fields[$name]));
    }
}