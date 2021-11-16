<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HBS_PD_PLAN_BENEFIT extends BaseModelDB2
{
    protected $table = 'pd_plan_benefit';
    protected $primaryKey = 'plbe_oid';

    public function PD_BEN_HEAD()
    {
        return $this->belongsTo('App\HBS_PD_BEN_HEAD', 'behd_oid', 'behd_oid');
    }
}
