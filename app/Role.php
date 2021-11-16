<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends \Spatie\Permission\Models\Role
{
    protected $table    = 'roles';
    protected $guarded  = ['id'];
    protected static $table_static = 'roles';
    protected $dates = ['deleted_at'];
}
