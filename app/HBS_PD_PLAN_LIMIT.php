<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HBS_PD_PLAN_LIMIT extends BaseModelDB2
{
    protected $table = 'pd_plan_limit';
    protected $primaryKey = 'plli_oid';
    public function PD_PLAN()
    {
        return $this->belongsTo('App\HBS_PD_PLAN', 'plan_oid', 'plan_oid');
    }


    public function PD_BEN_HEAD()
    {
        return $this->belongsToMany('App\HBS_PD_BEN_HEAD','pd_plan_benefit', 'plli_oid', 'behd_oid');
    }
}
