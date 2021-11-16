<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class UncSign extends BaseModel
{
    protected $table = 'unc_sign';
    protected static $table_static = 'unc_sign';
    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];
    
}
