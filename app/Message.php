<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\FullTextSearch;
class Message extends BaseModel
{
    use FullTextSearch;
    protected $table = 'messages';
    protected static $table_static = 'messages';
    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];
    protected $searchable = [
        'message'
    ];

    public function userTo()
    {
        return $this->belongsTo(User::class,'user_to')->select('email','id','name','avantar');
    }
    public function userFrom()
    {
        return $this->belongsTo(User::class,'user_from')->select('email','id','name','avantar');
    }
}
