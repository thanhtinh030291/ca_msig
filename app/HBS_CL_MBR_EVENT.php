<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HBS_CL_MBR_EVENT extends BaseModelDB2
{
    protected $table = 'cl_mbr_event';
    protected $guarded = ['id'];
    public function HBS_MR_MEMBER()
    {
        return $this->belongsTo('App\HBS_MR_MEMBER', 'memb_oid', 'memb_oid');
    }
    
}
