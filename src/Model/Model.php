<?php

namespace WorldNewsGroup\Marketo\Model;

class Model {
    public static $fields = [];
    
    /**
     * @internal
     */
    protected $values;

    /**
     * @internal
     */
    protected function __construct(Array $data) {
        $this->values = $data;
    }

    /**
     * @internal
     */
    public function __get($name) {
        if( in_array($name, self::$fields) && isset(self::$fields[$name]) ) {
            return $this->values['result'][$name];
        }
    }

    /**
     * @internal
     */
    public function __isset($name) {
        return (in_array($name, self::$fields) && isset(self::$fields[$name]));
    }
}