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
        if( isset($this->values[$name]) ) {
            return $this->values[$name];
        }
    }

    /**
     * @internal
     */
    public function __isset($name) {
        return (isset($this->values[$name]));
    }
}