<?php

namespace App;
use Illuminate\Database\Eloquent\Model;


class LogMfile extends Model
{
    protected $table = 'log_mfile';
    protected static $table_static = 'log_mfile';
    protected $guarded = ['id'];

}
