<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HBS_VW_RP_POLICY_PLAN_BEN_TYPE extends BaseModelDB2
{
    protected $table = 'vw_rp_policy_plan_ben_type';
    protected $primaryKey = 'pobe_oid';

    public function PD_PLAN()
    {
        return $this->belongsTo('App\HBS_PD_PLAN', 'plan_oid', 'plan_oid');
    }
}
