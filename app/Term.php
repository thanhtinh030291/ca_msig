<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Term extends BaseModel
{
    protected $table = 'terms';
    protected static $table_static = 'terms';
    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];
    public function userUpdated()
    {
        return $this->hasOne('App\User', 'id', 'updated_user');
    }

    public function userCreated()
    {
        return $this->hasOne('App\User', 'id', 'created_user');
    }

    public function getFullTextTermAttribute(){
        $q = '<li><span style="font-size: 10pt; font-family: arial, helvetica, sans-serif;">Điều '.$this->name  .": ". preg_replace('/(<p>)/', "", $this->description.'</span>' ). '</li>';
        return $q; 
    }

    public function getFullTextTermENAttribute(){
        $q = '<li><span style="font-size: 10pt; font-family: arial, helvetica, sans-serif;"> ' .$this->name  .": ". preg_replace('/(<p>)/', "", $this->description_en.'</span>' ) . '</li>';
        return $q;
    }
}
