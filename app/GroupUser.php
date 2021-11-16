<?php

namespace App;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class GroupUser
 * @package App
 * @version September 9, 2020, 11:11 am +07
 *
 * @property string name
 * @property integer lead
 * @property integer active_leader
 * @property integer supper
 * @property integer active_supper
 * @property integer assistant_manager
 * @property integer active_assistant_manager
 * @property integer manager
 * @property integer active_manager
 * @property integer header
 * @property integer active_header
 * @property integer created_user
 * @property integer updated_user
 * @property integer is_deleted
 */
class GroupUser extends BaseModel
{
    use SoftDeletes;

    public $table = 'group_users';
    protected static $table_static = 'group_users';
    
    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'name',
        'lead',
        'active_leader',
        'supper',
        'active_supper',
        'assistant_manager',
        'active_assistant_manager',
        'manager',
        'active_manager',
        'header',
        'active_header',
        'created_user',
        'updated_user',
        'is_deleted'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'lead' => 'integer',
        'active_leader' => 'integer',
        'supper' => 'integer',
        'active_supper' => 'integer',
        'assistant_manager' => 'integer',
        'active_assistant_manager' => 'integer',
        'manager' => 'integer',
        'active_manager' => 'integer',
        'header' => 'integer',
        'active_header' => 'integer',
        'created_user' => 'integer',
        'updated_user' => 'integer',
        'is_deleted' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
