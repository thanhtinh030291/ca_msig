<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MANTIS_BUG extends BaseModelMantis
{
    protected $table = 'mantis_bug_table';
    protected $guarded = ['id'];

    public function CUSTOM_FIELD_STRING()
    {
        return $this->hasMany('App\MANTIS_CUSTOM_FIELD_STRING', 'bug_id', 'id');
    }

    public function BUG_TEXT()
    {
        return $this->hasOne('App\MANTIS_BUG_TEXT', 'id', 'bug_text_id');
    }
}
