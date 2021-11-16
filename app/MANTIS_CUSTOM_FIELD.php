<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MANTIS_CUSTOM_FIELD extends BaseModelMantis
{
    protected $guarded = ['id'];
    protected $table = 'mantis_custom_field_table';
    
}
