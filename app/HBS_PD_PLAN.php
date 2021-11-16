<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HBS_PD_PLAN extends BaseModelDB2
{
    protected $table = 'pd_plan';
    protected $primaryKey = 'plan_oid';
    public function MR_POLICY_PLAN()
    {
        return $this->hasMany('App\HBS_MR_POLICY_PLAN', 'plan_oid', 'plan_oid');
    }
    public function PD_PLAN_LIMIT()
    {
        return $this->hasMany('App\HBS_PD_PLAN_LIMIT', 'plan_oid', 'plan_oid');
    }
}
