<?php

namespace WorldNewsGroup\Marketo\Model;

class ProgramMembership extends Model {
    protected $allowedFields = [
        'acquiredBy',
        'isExhausted',
        'membershipDate',
        'nurtureCadence',
        'progressionStatus',
        'reachedSuccess',
        'stream'
    ];
}