<?php

namespace App;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Provider
 * @package App
 * @version June 26, 2020, 11:20 am +07
 *
 * @property string PROV_CODE
 * @property string EFF_DATE
 * @property string TERM_DATE
 * @property string PROV_NAME
 * @property string ADDR
 * @property string SCMA_OID_COUNTRY
 * @property string PAYEE
 * @property string BANK_NAME
 * @property string CL_PAY_ACCT_NO
 * @property string BANK_ADDR
 */
class Provider extends BaseModel
{
    use SoftDeletes;

    public $table = 'provider';
    protected static $table_static = 'provider';
    
    
    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'PROV_CODE',
        'EFF_DATE',
        'TERM_DATE',
        'PROV_NAME',
        'ADDR',
        'SCMA_OID_COUNTRY',
        'PAYEE',
        'BANK_NAME',
        'CL_PAY_ACCT_NO',
        'BANK_ADDR'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'PROV_CODE' => 'string',
        'EFF_DATE' => 'date',
        'TERM_DATE' => 'date',
        'PROV_NAME' => 'string',
        'ADDR' => 'string',
        'SCMA_OID_COUNTRY' => 'string',
        'PAYEE' => 'string',
        'BANK_NAME' => 'string',
        'CL_PAY_ACCT_NO' => 'string',
        'BANK_ADDR' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function deduct_provider(){
        return $this->hasMany('App\DeductProvider', 'provider_id')->orderBy('id', 'desc');
    }

    public function getTotalDeductAttribute(){
        return $this->deduct_provider->sum('amt');
    }
}
