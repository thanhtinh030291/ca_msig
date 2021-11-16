<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HBS_PD_BEN_HEAD extends BaseModelDB2
{
    protected $table = 'pd_ben_head';
    protected $guarded = ['behd_oid'];
    protected $primaryKey = 'behd_oid';

    public function PD_PLAN_LIMIT()
    {
        return $this->belongsToMany('App\HBS_PD_PLAN_LIMIT','pd_plan_benefit', 'behd_oid', 'plli_oid' )
        ->withPivot(['plli_oid' , 'behd_oid']);

    }
    
    public function PD_BEN_HEAD_LANG()
    {
        return $this->hasOne('App\HBS_PD_BEN_HEAD_LANG','behd_oid', 'behd_oid');
    }
}
