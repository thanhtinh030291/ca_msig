<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\ClaimWordSheet;
use App\HBS_MR_MEMBER;
use App\ItemOfClaim;
use App\User;
use App\Claim;
use App\HBS_CL_CLAIM;
use App\ReasonReject;
use Carbon\Carbon;
use Config;
use DB;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Storage;
use App\Http\Controllers\SendMessageController;
class PanoramaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', []);
    }

    public function panorama($mem_ref_no)
    {
        $mem_ref_no = str_pad($mem_ref_no,10,"0",STR_PAD_LEFT);
        $member = HBS_MR_MEMBER::where('MEMB_REF_NO', $mem_ref_no)->first();
        if($member == null){
            return response()->json(['status' => 'error', 'message' => 'not exit member ref no'], 200);
        }
        $claim_line = $member->ClaimLine;
        $data['full_name'] = $member->mbr_last_name ." " . $member->mbr_first_name;
        $data['dob'] = Carbon::parse($member->dob)->format('d/m/Y');
        $data['sex'] = str_replace("SEX_", "",$member->scma_oid_sex);
        $data['member_no'] = $member->mbr_no;
        $data['effective_date'] = $member->pocyEffdate;
        $data['status'] = $member->statusQuery;
        $data['plan'] = $member->plan;
        $data['occupation_loading'] = $member->occupation;
        $data['loading'] = $member->MR_MEMBER_EVENT->where('scma_oid_event_code', 'EVENT_CODE_EXPL')->first() ? $member->MR_MEMBER_EVENT->where('scma_oid_event_code', 'EVENT_CODE_EXPL')->first()->event_desc : "";
        $data['exclusion'] = $member->MR_MEMBER_EVENT->where('scma_oid_event_code', 'EVENT_CODE_EXCL')->first() ? $member->MR_MEMBER_EVENT->where('scma_oid_event_code', 'EVENT_CODE_EXCL')->first()->event_desc : "";
        $data['claim_history'] = [];
        $data['claim_assistant'] = [];
        $claim_ids = collect($claim_line->toArray())->unique('clam_oid')->pluck('clam_oid')->toArray();
        $condition = function ($q) {

            $q->where('approve',"!=",null);
        };
        $claim = Claim::whereIn('code_claim', $claim_ids)->ItemClaimReject()->with(['export_letter'=>$condition])->get();
        
        foreach ($claim_line as $key => $item) {
            $data['claim_history'][$key]['claim_oid'] =  $item->clam_oid;
            $data['claim_history'][$key]['date'] = Carbon::parse($item->incur_date_from)->format('d/m/Y') .' - '.Carbon::parse($item->incur_date_to)->format('d/m/Y');
            $data['claim_history'][$key]['prov_name'] =  $item->prov_name;
            $data['claim_history'][$key]['diagnosis'] = $item->RT_DIAGNOSIS->diag_desc_vn;
            $data['claim_history'][$key]['treatment'] = str_replace("BENEFIT_TYPE_", "", $item->PD_BEN_HEAD->scma_oid_ben_type) . ' - ' . $item->PD_BEN_HEAD->ben_head;
            $data['claim_history'][$key]['app_amt'] = formatPrice($item->app_amt);            
        }

        foreach ($claim as $key => $value) {
            $data['claim_assistant'][$key]['claim_oid'] = $value->code_claim;
            $data['claim_assistant'][$key]['reject'] = [];
            foreach ($value->item_of_claim as $key2 => $value2) {
                $data['claim_assistant'][$key]['reject'][$key2]['content'] = $value2->content;
                $data['claim_assistant'][$key]['reject'][$key2]['amount'] = $value2->amount;
                $arrKeyRep = [ '[##nameItem##]' , '[##amountItem##]' , '[##Date##]' , '[##Text##]' ];
                $template_new = $value2->reason_reject->template;
                foreach ( $arrKeyRep as $key3 => $value3) {
                    $template_new = str_replace($value3, '$parameter', $template_new);
                };
                $data['claim_assistant'][$key]['reject'][$key2]['reason_reject'] =  Str::replaceArray('$parameter', $value2->parameters, $template_new);
            }
            foreach ( $value->export_letter as $key4 => $value4) {
                $data['claim_assistant'][$key]['letter'][$key4] = $value4->approve;
            }
        }
        return response()->json(['status' => 'success', 'message' =>'get data success', 'data' => $data], 200);
    }
}
