<?php

namespace App;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ReportAdmin
 * @package App
 * @version October 23, 2020, 9:26 am +07
 *
 * @property string MEMB_NAME
 * @property string POCY_REF_NO
 * @property string MEMB_REF_NO
 * @property integer PRES_AMT
 * @property string INV_NO
 * @property string PROV_NAME
 * @property string RECEIVE_DATE
 * @property integer REQUEST_SEND
 * @property string SEND_DLVN_DATE
 * @property integer created_user
 * @property integer updated_user
 * @property integer is_deleted
 */
class ReportAdmin extends BaseModel
{
    use SoftDeletes;

    public $table = 'report_admin';
    protected static $table_static = 'report_admin';
    
    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'MEMB_NAME',
        'POCY_REF_NO',
        'MEMB_REF_NO',
        'PRES_AMT',
        'INV_NO',
        'PROV_NAME',
        'RECEIVE_DATE',
        'REQUEST_SEND',
        'SEND_DLVN_DATE',
        'created_user',
        'updated_user',
        'CL_NO',
        'is_deleted'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'MEMB_NAME' => 'string',
        'POCY_REF_NO' => 'string',
        'MEMB_REF_NO' => 'string',
        'PRES_AMT' => 'integer',
        'INV_NO' => 'string',
        'PROV_NAME' => 'string',
        'RECEIVE_DATE' => 'datetime',
        'REQUEST_SEND' => 'integer',
        'SEND_DLVN_DATE' => 'date',
        'created_user' => 'integer',
        'updated_user' => 'integer',
        'is_deleted' => 'integer',
        'CL_NO' => 'string',
        'claim_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
