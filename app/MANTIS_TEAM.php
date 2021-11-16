<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MANTIS_TEAM extends BaseModelMantis
{
    protected $table = 'mantis_plugin_advancedgroup_team_table';
    protected $guarded = ['id'];
    public function USER_GROUP()
    {
        return $this->hasMany('App\MANTIS_USER_GROUP', 'team_id', 'id');
    }

    public function getLeadInfoAttribute()
    {
        return MANTIS_USER::findOrFail($this->leader_id);
    }
}
