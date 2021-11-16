<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InboxEmail extends Model
{
    //
    public $table = 'inbox_email';
    protected $guarded = ['id'];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'to' => 'array',
    ];
    protected function castAttribute($key, $value)
    {
        if ($this->getCastType($key) == 'array' && is_null($value)) {
            return [];
        }
        return parent::castAttribute($key, $value);
    }
}
