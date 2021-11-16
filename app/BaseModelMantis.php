<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseModelMantis extends Model
{
    protected $connection = 'mysql_mantis';
    public $timestamps = false;
}
