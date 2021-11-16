<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HBS_MR_POLICY_PLAN_BENEFIT extends BaseModelDB2
{
    protected $table = 'mr_policy_plan_benefit';
    protected $primaryKey = 'pobe_oid';
    public function PD_PLAN_BENEFIT()
    {
        return $this->belongsTo('App\HBS_PD_PLAN_BENEFIT', 'plbe_oid', 'plbe_oid');
    }
}
