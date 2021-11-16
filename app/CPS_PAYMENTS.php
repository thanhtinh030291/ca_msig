<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CPS_PAYMENTS extends BaseModelCPS
{
    protected $table = 'payments';
    protected $guarded = ['PAYM_ID'];

}
