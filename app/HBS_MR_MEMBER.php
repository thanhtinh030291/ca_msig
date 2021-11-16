<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class HBS_MR_MEMBER extends  BaseModelDB2
{
    protected $table = 'MR_MEMBER';
    protected $guarded = ['id'];
    protected $primaryKey = 'memb_oid';
    public function MR_MEMBER_PLAN()
    {
        return $this->hasMany('App\HBS_MR_MEMBER_PLAN', 'memb_oid', 'memb_oid');
    }
    public function MR_MEMBER_EVENT()
    {
        return $this->hasMany('App\HBS_MR_MEMBER_EVENT', 'memb_oid', 'memb_oid');
    }
    public function getClaimLineAttribute()
    {
        $CL_LINE = HBS_CL_LINE::where('memb_oid',$this->memb_oid)->where('REV_DATE', null)->get();
        return $CL_LINE;
    }
    
    public function getMrMemberEventAttribute()
    {
        $MR_MBR_EVENT = HBS_MR_MEMBER_EVENT::where('memb_oid',$this->memb_oid)->get();
        return $MR_MBR_EVENT;
    }

    public function getClaimMemberEventAttribute()
    {

        $CL_MBR_EVENT = HBS_CL_MBR_EVENT::where('memb_oid',$this->memb_oid)->get();
        return $CL_MBR_EVENT;
    }
    
    public function getPocyEffdateAttribute()
    {
        $MR_MEMBER_PLAN = $this->MR_MEMBER_PLAN->first();
        if($MR_MEMBER_PLAN){
            return Carbon::parse($MR_MEMBER_PLAN->eff_date)->format('d/m/Y');
        }
        return "";
    }

    public function getOccupationAttribute()
    {
        $MR_MEMBER_PLAN = $this->MR_MEMBER_PLAN->sortByDesc('eff_date')->first();
        $occupation = [];
        if(!empty($MR_MEMBER_PLAN)){
            foreach ($MR_MEMBER_PLAN->MR_POLICY_PLAN_BENEFIT as $key => $value) {
                $pre = $value->PD_PLAN_BENEFIT->PD_BEN_HEAD->scma_oid_ben_type;
                switch ($pre) {
                    case 'BENEFIT_TYPE_IP':
                        
                            $occupation[] = "IP {$value->pivot->occuation_ldg_pct}%";
                        
                        break;
                    case 'BENEFIT_TYPE_OP':
                            if($value->pivot->occuation_ldg_pct != null)
                                $occupation[] = "OP {$value->pivot->occuation_ldg_pct}%";
                            break;
                    default:
                        break;
                }
            }
        }
        return array_unique($occupation);
    }

    public function getPlanAttribute()
    {
        //return [];
        $plan = [];
        $DLVN_MEMBER = self::where('MBR_NO' , $this->mbr_no)->get();
        foreach($this->MR_MEMBER_PLAN as $key2 => $value2){
            $plan[] =  $value2->MR_POLICY_PLAN->pocy_plan_desc ." - ".Carbon::parse($value2->MR_POLICY_PLAN->crt_date)->format('Y');
        }
            
        
        return $plan;
    }

    public function getStatusQueryAttribute(){
        //return " ";
        $headers = [
            'Content-Type' => 'application/json',
        ];
        $client = new \GuzzleHttp\Client([
            'headers' => $headers
        ]);
        
        // try {
        //     $request = $client->get(config('constants.url_query_online').$this->memb_ref_no);
        //     $response = $request->getBody()->getContents();
        //     $response = json_decode($response,true);
        //     if(data_get($response, 'response_msg.msg_code') == "DLVN0"){
        //         $html = "";
        //         foreach(data_get($response, 'client_info', []) as $key => $value){
        //             $html .= "<span class = 'ml-2'>" . data_get($value ,'sPlanID') . ": ".  data_get($value ,'sStatus')  . "</span>";
        //         }
        //         return $html;
        //     }else{
        //         return "";
        //     }
        // } catch ( Exception $e) {
        //     return "";
        // }
        return "";
    }

    public function getMessageComfirmConractAttribute(){
        $headers = [
            'Content-Type' => 'application/json',
        ];
        $client = new \GuzzleHttp\Client([
            'headers' => $headers
        ]);
        
        try {
            $request = $client->get(config('constants.url_query_online').$this->memb_ref_no);
            $response = $request->getBody()->getContents();
            $response = json_decode($response,true);

            if(data_get($response, 'response_msg.msg_code') == "DLVN0"){
                $client_info = collect($response['client_info'])->whereNotIn('sStatus', ['Not-Taken']);
                $all_lapse_process_date = collect($response['all_lapse_process_date']);
                $all_lapse_effective_date = collect($response['all_lapse_effective_date']);
                $all_reinstate_date = collect($response['all_reinstate_date']);
                $map_ql = $client_info->map(function ($item, $key) {
                    $t = "";
                    switch (substr(trim(data_get($item,'sPlanID')), 3,1)) {
                        case 'O':
                            $t = "OP";
                            break;
                        case 'I':
                            $t = "IP";   
                            break;
                        default:
                            $t = "DT";
                            break;
                    }
                    return  $t ."-" .substr(trim(data_get($item,'dFaceAmount')),0,3);
                });
                
                $html = 'Dear DLVN ' . "\n". "\n";
                $html .= 'Vui lòng xác nhận tình trạng hợp đồng.' . "\n";
                $html .= "Quyền lợi: " .implode(", ",$map_ql->toArray()) . "\n";
                $html .= "Hiệu lực: " . Carbon::parse($client_info->first()['sCoverageIssueDate'])->format('d/m/Y') . "\n";
                $html .= "Tình trạng: ". $client_info->first()['sStatus'] . "\n". "\n";
                if($all_lapse_effective_date->count() > 0 ){
                    $html .=  "Lapse effective: ".Carbon::parse($all_lapse_effective_date->first()['sDate'])->format('d/m/Y') . "\n";
                }
                if($all_lapse_process_date->count() > 0 ){
                    $html .=  "Lapse process: ".Carbon::parse($all_lapse_process_date->first()['sDate'])->format('d/m/Y') . "\n";
                }
                if($all_reinstate_date->count() > 0 ){
                    $html .=  "Reinstate: ".Carbon::parse($all_reinstate_date->first()['sDate'])->format('d/m/Y') . "\n";
                }
                $html .=  "\n"."Thanks." . "\n";
                return $html;
            }else{
                return "";
            }
        } catch ( Exception $e) {
            return "";
        }
        
    }

    public function getQueryOnlineAttribute(){
        
        $headers = [
            'Content-Type' => 'application/json',
        ];
        $client = new \GuzzleHttp\Client([
            'headers' => $headers
        ]);
        try {
            $request = $client->get(config('constants.url_query_online').$this->memb_ref_no);
            $response = $request->getBody()->getContents();
            return $response;

        } catch (Exception $e) {
            return "";
        }
    }

    public function getBankNameChangeAttribute()
    {
        $HBS_SY_SYS_CODE = HBS_SY_SYS_CODE::selectRaw("scma_oid , FN_GET_SYS_CODE_DESC(scma_oid, 'en') name")->where("scma_oid","LIKE","%MEMB_BANK_NAME_%")->pluck("name","scma_oid");
        preg_match('/(MEMB_BANK_NAME_)/', $this->bank_name, $matches, PREG_OFFSET_CAPTURE);

        return $matches ? data_get($HBS_SY_SYS_CODE ,$this->bank_name) : $this->bank_name;
    }
    
    public function getCashBankNameChangeAttribute()
    {
        $HBS_SY_SYS_CODE = HBS_SY_SYS_CODE::selectRaw("scma_oid , FN_GET_SYS_CODE_DESC(scma_oid, 'en') name")->where("scma_oid","LIKE","%MEMB_BANK_NAME_%")->pluck("name","scma_oid");
        preg_match('/(MEMB_BANK_NAME_)/', $this->cash_bank_name, $matches, PREG_OFFSET_CAPTURE);
        return $matches ? data_get($HBS_SY_SYS_CODE,$this->cash_bank_name) : $this->cash_bank_name;
    }
}
