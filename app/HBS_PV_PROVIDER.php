<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HBS_PV_PROVIDER extends BaseModelDB2
{
    protected $table = 'pv_provider';
    protected $primaryKey = 'prov_oid';
    
    public function CL_LINE()
    {
        return $this->hasMany('App\HBS_CL_LINE', 'prov_oid', 'prov_oid');
    }
}
