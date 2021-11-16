<?php

namespace App;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RoomAndBoard
 * @package App
 * @version November 6, 2019, 7:25 am UTC
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
    protected $guarded = ['id'];
    protected $casts = [
        'line_rb'  => 'array',
    ];
    protected function castAttribute($key, $value)
    {
        if ($this->getCastType($key) == 'array' && is_null($value)) {
            return [];
        }
        return parent::castAttribute($key, $value);
    }
    
    protected $dates = ['deleted_at'];

    
    
    

    

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
