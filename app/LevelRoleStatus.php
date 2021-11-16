<?php

namespace App;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class LevelRoleStatus
 * @package App
 * @version February 24, 2020, 5:34 pm +07
 *
 * @property string name
 * @property integer min_amount
 * @property integer max_amount
 * @property integer begin_status
 * @property integer end_status
 * @property integer created_user
 * @property integer updated_user
 * @property integer is_deleted
 */
class LevelRoleStatus extends BaseModel
{
    use SoftDeletes;

    public $table = 'level_role_status';
    protected static $table_static = 'level_role_status';
    
    
    protected $dates = ['deleted_at'];

    protected $guarded  = ['id'];
    
    public function transaction_role_status()
    {
        return $this->hasMany('App\TransactionRoleStatus', 'level_role_status_id', 'id')->orderBy('role')->orderBy('current_status');
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
