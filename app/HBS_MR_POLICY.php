<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HBS_MR_POLICY extends BaseModelDB2
{
    protected $table = 'mr_policy';
    protected $primaryKey = 'pocy_oid';
    public function MR_POLICY_PLAN()
    {
        return $this->hasMany('App\HBS_MR_POLICY_PLAN', 'pocy_oid', 'pocy_oid');
    }

    public function HBS_CM_BROKER()
    {
        return $this->belongsTo('App\HBS_CM_BROKER','brkr_oid_pri_corr','brkr_oid');
    }

    public function HBS_CM_BROKER_FRONTLINER()
    {
        return $this->belongsTo('App\HBS_CM_BROKER','BRKR_OID_FRONT_LINER','brkr_oid');
    }

}
