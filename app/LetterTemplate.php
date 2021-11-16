<?php

namespace App;
use Schema;

class LetterTemplate extends BaseModel
{
    protected $table    = 'letter_template';
    protected $guarded  = ['id'];
    protected static $table_static = 'letter_template';
    protected $dates = ['deleted_at'];
    

    public function userUpdated()
    {
        return $this->hasOne('App\User', 'id', 'updated_user');
    }

    public function userCreated()
    {
        return $this->hasOne('App\User', 'id', 'created_user');
    }

}
