<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HBS_CL_LINE_REJ extends BaseModelDB2
{
    protected $table = 'cl_line_rej';
    protected $guarded = ['id'];
    public function HBS_CL_LINE()
    {
        return $this->belongsTo('App\HBS_CL_LINE', 'clli_oid', 'clli_oid');
    }
    
}
