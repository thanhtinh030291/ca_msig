<?php

namespace App;

use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;

class BaseModelDB2 extends Eloquent
{
    protected $connection = 'oracle';
    public $timestamps = false;
}
