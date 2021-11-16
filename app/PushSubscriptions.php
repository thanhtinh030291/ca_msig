<?php

namespace App;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TransactionRoleStatus
 * @package App
 * @version February 24, 2020, 8:52 pm +07
 *
 * @property integer level_role_status_id
 * @property integer current_status
 * @property integer role
 * @property integer to_status
 * @property integer is_deleted
 * @property integer created_user
 * @property integer updated_user
 */
class PushSubscriptions extends Model
{
    

    public $table = 'push_subscriptions';
    protected static $table_static = 'push_subscriptions';
    
    protected $guarded = ['id'];
    
    public static $rules = [
        
    ];

    
}
