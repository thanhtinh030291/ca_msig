<?php

namespace App;

use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;

class BaseModelDB3 extends Eloquent
{
    protected $connection = 'oracle-data-annaly';

}
