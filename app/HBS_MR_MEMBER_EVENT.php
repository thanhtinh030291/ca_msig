<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HBS_MR_MEMBER_EVENT extends  BaseModelDB2
{
    protected $table = 'MR_MEMBER_EVENT';
    protected $guarded = ['id'];
    protected $primaryKey = 'meev_oid';
    public function MR_MEMBER()
    {
        return $this->belongsTo('App\HBS_MR_MEMBER', 'memb_oid', 'memb_oid');
    }
   
}
