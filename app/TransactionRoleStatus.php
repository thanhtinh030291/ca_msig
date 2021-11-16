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
class TransactionRoleStatus extends BaseModel
{
    use SoftDeletes;

    public $table = 'transaction_role_status';
    protected static $table_static = 'transaction_role_status';
    
    
    protected $dates = ['deleted_at'];
    protected $guarded = ['id'];
    
    public function level_role_status()
    {
        return $this->belongsTo('App\LevelRoleStatus', 'id', 'level_role_status_id');
    }

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
