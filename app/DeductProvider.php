<?php

namespace App;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class DeductProvider extends BaseModel
{
    use SoftDeletes;

    public $table = 'deduct_provider';
    protected static $table_static = 'deduct_provider';
    protected $guarded = ['id'];
    
    protected $dates = ['deleted_at'];
}
