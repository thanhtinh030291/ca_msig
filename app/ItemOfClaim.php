<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemOfClaim extends Model {
    protected $table   = 'item_of_claim';
    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];
    protected $casts   = [
        'parameters' => 'array',
    ];
    protected function castAttribute($key, $value)
    {
        if ($this->getCastType($key) == 'array' && is_null($value)) {
            return [];
        }
        return parent::castAttribute($key, $value);
    }
    public function reason_reject()
    {
        return $this->hasOne('App\ReasonReject', 'id', 'reason_reject_id');
    }
}
