<?php

namespace App;
use Illuminate\Database\Eloquent\Model;


class LogHbsApproved extends Model
{
    protected $table = 'log_hbs_approved';
    protected static $table_static = 'log_hbs_approved';
    protected $guarded = ['id'];
    public function export_letter()
    {
        return $this->belongsTo('App\ExportLetter', 'export_letter_id', 'id');
    }

}
