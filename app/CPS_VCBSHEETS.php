<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CPS_VCBSHEETS extends BaseModelCPS
{
    protected $table = 'vcbsheets';
    protected $guarded = ['VNBS_ID'];
    public function vnbt_sheets()
    {
        return $this->hasMany('App\CPS_VNBT_SHEETS', 'SHEET_ID', 'SHEET_ID');
    }
}
