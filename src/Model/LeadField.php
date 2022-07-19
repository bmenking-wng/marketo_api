<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Client;

class LeadField extends Model {
    public static $fields = [
        'id',
        'firstName',
        'lastName',
        'email',
        'updatedAt',
        'createdAt'
    ];

    private $values;

    public function __construct($import) {
        $this->values = $import;
    }

}