<?php

namespace App;

class Claim extends BaseModel
{
    protected $guarded = ['id'];
    protected $table = 'claim';
    protected static $table_static = 'claim';
    protected $dates = ['deleted_at'];
    protected $casts = [
        'manager_gop_accept_pay' => 'array',
    ];
    
    public function hospital_request()
    {
        return $this->hasOne('App\HospitalRequest', 'claim_id')->orderBy('id', 'desc');
    }

    public function inbox_email()
    {
        return $this->hasOne('App\InboxEmail', 'claim_id')->orderBy('id', 'desc');
    }

    public function report_admin()
    {
        return $this->hasOne('App\ReportAdmin', 'claim_id')->orderBy('id', 'desc');
    }

    public function finish_and_pay()
    {
        return $this->hasOne('App\FinishAndPay', 'claim_id')->orderBy('id', 'desc');
    }
    
    public function log_unfreezed()
    {
        return $this->hasMany('App\LogUnfreezed', 'claim_id')->orderBy('id', 'desc');
    }
    public function export_letter()
    {
        return $this->hasMany('App\ExportLetter', 'claim_id')->orderBy('id', 'desc');
    }

    public function export_letter_last()
    {
        return $this->hasOne('App\ExportLetter', 'claim_id')->orderBy('id', 'desc')->latest();
    }

    public function item_of_claim()
    {
        return $this->hasMany('App\ItemOfClaim', 'claim_id');
    }

    public function claim_word_sheet()
    {
        return $this->hasOne('App\ClaimWordSheet', 'claim_id', 'id');
    }

    public function getCodeClaimHBSAttribute()
    {
        $data = HBS_CL_CLAIM::findOrFail($this->code_claim);
        return $data->cl_no;
    }

    public function getClClaimAttribute(){
        $data = HBS_CL_CLAIM::findOrFail($this->code_claim);
        return $data;
    }

    //get CSR file 
    public function getCsrFileAttribute(){
        $csr  = HBS_SY_RPT_IDX::where('rpct_oid','like','%CLSETTRPT%')->where('idx_val',"like","%{$this->code_claim_show}%")->orderBy('upd_date', 'DESC')->get();
        
        return $csr;
    }

    //get reject code hbs
    public function getRejectCodeAttribute(){
        $condition_re = function ($q){
            $q->selectRaw("fn_get_sys_code_desc_vn(SCMA_OID_CL_REJ_CODE) content, ROUND(rej_amt) amount , (null) id , clli_oid");
            $q->where('rej_amt', '>', 0);
        };

        $condition = function ($q) use ($condition_re){
            $q->with(['HBS_CL_LINE_REJ' => $condition_re]);
        };
        $HBS_CL_CLAIM = HBS_CL_CLAIM::with(['HBS_CL_LINE' => $condition])->findOrFail($this->code_claim);
        
        
        return $HBS_CL_CLAIM->HBS_CL_LINE->pluck('HBS_CL_LINE_REJ')->toArray();
    }

    public static function storeFile($file ,  $dirUpload){
        $imageName = time() . md5($file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();
        $file->storeAs($dirUpload, $imageName);
        return $imageName;
    }

    
    public function scopeItemClaimReject($query)
    {
        $conditionTerm = function ($q) {
            $q->with('term');
        };
        $condition = function ($q) use ($conditionTerm) {
            $q->whereNotNull('reason_reject_id');
            $q->with(['reason_reject'=>$conditionTerm]);
        };
        return $query->with(['item_of_claim' => $condition]);
    }

    public function LogMfile()
    {
        return $this->belongsTo('App\LogMfile', 'id', 'claim_id')->where('m_errorCode',0);
    }
}
