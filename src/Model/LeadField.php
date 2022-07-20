<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Client;

class LeadField extends Model {
    public static $fields = [
        'displayName',
        'name',
        'description',
        'dataType',
        'length',
        'isHidden',
        'isHtmlEncodingInEmail',
        'isSensitive',
        'isCustom',
        'isApiCreated'
    ];

    public function __construct($import) {
        $this->values = $import;
    }

}