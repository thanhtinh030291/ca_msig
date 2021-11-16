<?php

namespace App;
use Schema;

class PaymentHistory extends BaseModel
{
    protected $table    = 'payment_history';
    protected $guarded  = ['id'];
    protected static $table_static = 'payment_history';
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
