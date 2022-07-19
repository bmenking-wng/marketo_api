<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Client;

class LeadPartition extends Model {
    public static $fields = [
        'description',
        'id',
        'name'
    ];

    public function __construct($import) {
        $this->values = $import;
    }

}