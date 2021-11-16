<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MANTIS_USER extends BaseModelMantis
{
    protected $table = 'mantis_user_table';
    protected $guarded = ['id'];
    public function USER_GROUP(){
        return $this->hasOne('App\MANTIS_USER_GROUP', 'user_id', 'id');
    }
}
