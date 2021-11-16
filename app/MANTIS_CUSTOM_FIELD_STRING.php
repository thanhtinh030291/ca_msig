<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MANTIS_CUSTOM_FIELD_STRING extends BaseModelMantis
{
    protected $table = 'mantis_custom_field_string_table';
    protected $fillable = [
        'field_id', 
        'bug_id',
        'value',
        'text'
    ];
}
