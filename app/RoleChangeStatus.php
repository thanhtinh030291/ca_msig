<?php

namespace App;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RoleChangeStatus
 * @package App
 * @version February 24, 2020, 11:55 am +07
 *
 * @property string name
 * @property integer begin
 * @property integer end
 * @property integer created_user
 * @property integer updated_user
 * @property integer is_deleted
 */
class RoleChangeStatus extends BaseModel
{
    use SoftDeletes;

    public $table = 'role_change_status';
    protected static $table_static = 'role_change_status';
    
    
    protected $dates = ['deleted_at'];
    protected $guarded = ['id'];
    
    

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */


    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
