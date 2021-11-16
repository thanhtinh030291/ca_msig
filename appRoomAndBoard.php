<?php

namespace App;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RoomAndBoard
 * @package App
 * @version November 6, 2019, 7:23 am UTC
 *
 * @property string name
 * @property string code_claim
 * @property string time_start
 * @property string time_end
 * @property integer created_user
 * @property integer updated_user
 */
class RoomAndBoard extends BaseModel
{
    use SoftDeletes;

    public $table = 'room_and_boards';
    protected static $table_static = 'room_and_boards';
    
    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'name',
        'code_claim',
        'time_start',
        'time_end',
        'created_user',
        'updated_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'code_claim' => 'string',
        'time_start' => 'datetime',
        'time_end' => 'datetime',
        'created_user' => 'integer',
        'updated_user' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
