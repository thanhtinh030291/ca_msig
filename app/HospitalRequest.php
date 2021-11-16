<?php

namespace App;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class HospitalRequest extends BaseModel
{
    use SoftDeletes;

    public $table = 'hospital_request';
    protected static $table_static = 'hospital_request';
    protected $guarded = ['id'];
    
    protected $dates = ['deleted_at'];
    protected $casts = [
        'diagnosis' => 'array',
        'RBGOP' => 'array',
        'SURGOP' => 'array',
        'EXTBGOP' => 'array',
    ];
}
