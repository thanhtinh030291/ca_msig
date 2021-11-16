<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HBS_MR_POLICYHOLDER extends  BaseModelDB2
{
    protected $table = 'MR_POLICYHOLDER';
    protected $guarded = ['id'];
    protected $primaryKey = 'poho_oid';

}
