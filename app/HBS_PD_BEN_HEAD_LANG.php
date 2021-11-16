<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HBS_PD_BEN_HEAD_LANG extends BaseModelDB2
{
    protected $table = 'PD_BEN_HEAD_LANG';
    protected $guarded = ['behl_oid'];
    protected $primaryKey = 'behl_oid';
    
}
