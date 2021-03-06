<?php

namespace App;
use Schema;

class ReasonReject extends BaseModel
{
    protected $table    = 'reason_reject';
    protected $guarded  = ['id'];
    protected static $table_static = 'reason_reject';
    protected $dates = ['deleted_at'];
    

    public function userUpdated()
    {
        return $this->hasOne('App\User', 'id', 'updated_user');
    }

    public function userCreated()
    {
        return $this->hasOne('App\User', 'id', 'created_user');
    }

    public function getTermAttribute()
    {
        if(empty($this->term_id)){
            return null;
        }else{
            $term = explode(",",$this->term_id);
            return Term::whereIn('id',$term)->get();
        }
    }
}
