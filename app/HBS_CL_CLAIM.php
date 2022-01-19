<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HBS_CL_CLAIM extends  BaseModelDB2
{
    protected $table = 'CL_CLAIM';
    protected $guarded = ['id'];
    protected $primaryKey = 'clam_oid';

    public function HBS_CL_LINE()
    {
        return $this->hasMany('App\HBS_CL_LINE', 'clam_oid', 'clam_oid')->whereNull('rev_date');
    }

    public function getHistoryClaimCurrentYearCurrentAttribute(){
        $fisrtClLine = $this->HBS_CL_LINE->first();
        $year = \Carbon\Carbon::parse($fisrtClLine->incur_date_from)->format("Y");

        $memb_oid = $this->member->memb_oid;
        $claim_oids = HBS_CL_LINE::whereYear('incur_date_from','>=',$year-1)->whereYear('incur_date_from','<=',$year+1)->where('clam_oid',"!=",$this->clam_oid)->where('memb_oid',$memb_oid)->pluck('clam_oid')->unique()->toArray();
        return HBS_CL_CLAIM::whereIn('clam_oid',$claim_oids)->get();
    }
    public function getMemberAttribute()
    {
        $fisrtClLine = $this->HBS_CL_LINE->first();
        if($fisrtClLine){
            $memb_oid = $fisrtClLine->memb_oid;
            return HBS_MR_MEMBER::findOrFail($memb_oid);
        }
        return null;
    }
    public function getProviderAttribute(){
        $fisrtClLine = $this->HBS_CL_LINE->first();
        if($fisrtClLine){
            $prov_oid = $fisrtClLine->prov_oid;
            return HBS_PV_PROVIDER::findOrFail($prov_oid);
        }
        return null;
    }
    public function getPoliceAttribute()
    {
        $fisrtClLine = $this->HBS_CL_LINE->first();
        if($fisrtClLine){
            $popl_oid = $fisrtClLine->popl_oid;
            return HBS_MR_POLICY_PLAN::with('MR_POLICY')->findOrFail($popl_oid)->MR_POLICY;
        }
        return null;
    }

    public function getPolicePlanAttribute()
    {
        $fisrtClLine = $this->HBS_CL_LINE->first();
        if($fisrtClLine){
            $popl_oid = $fisrtClLine->popl_oid;
            return HBS_MR_POLICY_PLAN::findOrFail($popl_oid);
        }
        return null;
    }

    public function getBrokerAttribute()
    {
        return $this->police->HBS_CM_BROKER;
        
    }

    public function getFrontlinerAttribute()
    {
        return $this->police->HBS_CM_BROKER_FRONTLINER;
        
    }

    

    public function getFirstLineAttribute(){
        return $this->HBS_CL_LINE->first();
    }

    public function getPlanAttribute()
    {
        $plan = [];
        if(!empty($ClLine)){
            foreach ($ClLine as $key => $value) {
                $pre = $value->PD_BEN_HEAD->scma_oid_ben_type;
                switch ($pre) {
                    case 'BENEFIT_TYPE_IP':
                        switch ($value->MR_POLICY_PLAN->PD_PLAN->plan_id) {
                            case '0001':
                                $plan[] = 'IP 210M';
                                break;
                            case '0002':
                            case '0006':
                                $plan[] = 'IP 420M';
                                break;
                            default:
                                $plan[] ='IP 630M';
                                break;
                        }
                        break;
                    default:
                        switch ($value->MR_POLICY_PLAN->PD_PLAN->plan_id) {
                            case '0003':
                                $plan[] = 'OP 210M';
                                break;
                            case '0002':
                            case '0004':
                                $plan[] = 'OP 420M';
                                break;
                            default:
                                $plan[] ='IP 630M';
                                break;
                        }
                        break;
                }
            }
        }
        return array_unique($plan);
    }

    public function getApplicantNameAttribute(){
        $dbDate = \Carbon\Carbon::parse($this->member->dob);
        $diffYears = \Carbon\Carbon::now()->diffInYears($dbDate);
        if($diffYears >= 18){
            return $this->member->mbr_last_name ." " . $this->member->mbr_first_name;
        }else{
            return $this->policyHolder->poho_name_1;
        }
        
    }

    public function getMemberNameCapAttribute(){
        return $this->member->mbr_last_name ." " . $this->member->mbr_first_name;
    }


    public function getPayMethodAttribute(){
        return $this->firstLine->scma_oid_cl_payment_method;
    }

    public function getPolicyHolderAttribute(){
        $poho_oid = $this->member->poho_oid;
        return HBS_MR_POLICYHOLDER::findOrFail($poho_oid);
    }

    public function getSumPresAmtAttribute(){
        $clLines = $this->HBS_CL_LINE->toArray();
        $sum = array_sum(array_column($clLines,'pres_amt'));
        return round($sum);
    }

    public function getSumOrgPresAmtAttribute(){
        $clLines = $this->HBS_CL_LINE->toArray();
        $sum = array_sum(array_column($clLines,'org_pres_amt'));
        return round($sum);
    }

    public function getSumAppAmtAttribute(){
        $clLines = $this->HBS_CL_LINE->toArray();
        $sum = array_sum(array_column($clLines,'app_amt'));
        return round($sum);
    }

    public function getInvNoAttribute(){
        $clLines = $this->HBS_CL_LINE->toArray();
        $keywords = array_map('trim', preg_split("/(,|;)/", implode(",",array_column($clLines,'inv_no'))));
        $kq = implode(",",$keywords);
        return $kq;
    }
    
    //show RT_DIAGNOSIS
    public function scopeIOPDiag($query){
        $conditionPlanLimit = function($q){
            $q->with('PD_BEN_HEAD');
        };

        $conditionPD = function($q) use ($conditionPlanLimit){
            $q->with(['PD_PLAN_LIMIT' => $conditionPlanLimit]);
        };
        $conditionPL = function($q) use ($conditionPD){
            $q->with(['PD_PLAN' => $conditionPD]);
        };
        $condition = function ($q) use ($conditionPL){
            $q->with('RT_DIAGNOSIS');
            $q->with('PD_BEN_HEAD');
            $q->with(['MR_POLICY_PLAN'=>$conditionPL]);
            $q->where('REV_DATE', null);
        };
        return $query->with(['HBS_CL_LINE' => $condition]);
    }
    
    // show all info HBS
    public function scopeHBSData($query){
        $conditionPL = function($q){
            $q->with('PD_PLAN');
        };
        $condition = function ($q) use ($conditionPL){
            $q->with('RT_DIAGNOSIS');
            $q->with('PD_BEN_HEAD');
            $q->with(['MR_POLICY_PLAN'=>$conditionPL]);
            $q->where('REV_DATE', null);
        };
        return $query->with(['HBS_CL_LINE' => $condition]);
    }

    public function getAdminFeeAttribute(){
        return $this->HBS_CL_LINE->pluck('admin_fee')->sum();
    }
}
