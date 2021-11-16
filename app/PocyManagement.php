<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PocyManagement extends BaseModel
{
    public $table = 'pocy_management';
    protected $guarded = ['id'];
    protected static $table_static = 'pocy_management';
    protected $dates = ['deleted_at'];
}
