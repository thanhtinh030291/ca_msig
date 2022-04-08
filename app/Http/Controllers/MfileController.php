<?php

namespace App\Http\Controllers;
use App\User;
use Auth;
use App\PaymentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Exception;
use App\HBS_CL_CLAIM;
use Illuminate\Database\Eloquent\Builder;
use App\Claim;
use Illuminate\Support\Str;


class MfileController extends Controller
{
    
    //use Authorizable;
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['search_params'] = [
            'code_claim_show' => $request->get('code_claim_show'),
            'created_user' => $request->get('created_user'),
            'barcode' => $request->get('created_user')
        ];
        

        $miss_mfile = \App\Claim::findByParams($data['search_params'])->whereDoesntHave('LogMfile', function (Builder $query) {
            $query->where('m_errorCode', 0);
        })->with('LogMfile')->whereNotNull('url_file_sorted')->get();
        $data['admin_list'] = User::getListIncharge();
         //pagination result
        $data['limit_list'] = config('constants.limit_list');
        $data['limit'] = $request->get('limit');
        $per_page = !empty($data['limit']) ? $data['limit'] : Arr::first($data['limit_list']);
        $data['miss_mfile']  = $miss_mfile->paginate($per_page)->setPath(route('mfile.index'));
        return view('mfileManagement.index', $data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $claim_id = $id;
        $claim  = Claim::itemClaimReject()->findOrFail($claim_id);
        if($claim->url_file_sorted == null ){
            \App\LogMfile::updateOrCreate([
                'claim_id' => $claim_id,
            ],[
                'cl_no' => $claim->code_claim_show,
                'm_errorCode' => 999,
                'have_ca' => 1,
                'have_mfile' => 0
            ]);
            return response()->json(['errorCode' => 999 ,'errorMsg' => 'File không tồn tại']);
        }
        $HBS_CL_CLAIM = HBS_CL_CLAIM::IOPDiag()->findOrFail($claim->code_claim);
        $poho_oids = \App\HBS_MR_POLICYHOLDER::where('poho_ref_no', $HBS_CL_CLAIM->PolicyHolder->poho_ref_no)->pluck('poho_oid')->toArray();
        $pocy_ref_nos =  \App\HBS_MR_POLICY::whereIn('poho_oid',$poho_oids)->pluck('pocy_ref_no')->unique()->toArray();



        $handle = fopen(storage_path("app/public/sortedClaim/{$claim->url_file_sorted}"),'r');
        $treamfile = stream_get_contents($handle);
        $token = getTokenMfile();
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'bearer '.$token
        ];
        $body = [
            'mode' => config('constants.mode_mfile'),
            'policy_holder' => [
                "policy_holder_name" => strtoupper(Str::slug($HBS_CL_CLAIM->PolicyHolder->poho_name_1,' '))." (".$HBS_CL_CLAIM->PolicyHolder->poho_ref_no.")",
                "policy_holder_no" =>  $HBS_CL_CLAIM->PolicyHolder->poho_ref_no,
                "policy_holder_note" =>  "PO. " . implode(" + ", $pocy_ref_nos),

            ],
            'member' => [
                "member_name" => strtoupper(Str::slug($HBS_CL_CLAIM->member->mbr_last_name. " " .$HBS_CL_CLAIM->member->mbr_first_name,' ')) ." (".$HBS_CL_CLAIM->member->memb_ref_no.")",
                "member_no" =>  $HBS_CL_CLAIM->member->memb_ref_no,
                "is_terminated" => "0",
                "member_notes"=> ""
        
            ],
            'claim' => [
                "claim_info" => [
                    "claim_no" => $claim->code_claim_show,
                    "payee" => $claim->claim_type == "M" ? "Insured" : strtoupper(Str::slug($HBS_CL_CLAIM->Provider->prov_name , ' ')),
                    "claim_note" => "Note something",
                    "claim_type" => "",
                    "claim_lever" => "",
                ],
                "claim_file" =>  [
                    "file_extension" => "pdf",
                    "file_content" => $treamfile
                ]
            ]
        ];
        $client = new \GuzzleHttp\Client([
            'headers' => $headers
        ]);
        $response = $client->request("POST", config('constants.link_mfile').'uploadmfile' , ['form_params'=>$body]);
        $response =  json_decode($response->getBody()->getContents());
        if($response->errorCode == 0){
            \App\LogMfile::updateOrCreate([
                'claim_id' => $claim_id,
            ],[
                'cl_no' => $claim->code_claim_show,
                'm_errorCode' => $response->errorCode,
                'm_errorMsg' => $response->errorMsg,
                'm_policy_holder_id' => $response->info_policy_holder->policy_holder_id,
                'm_policy_holder_latest_version' => $response->info_policy_holder->policy_holder_latest_version,
                'm_member_id' => $response->info_member->member_id,
                'm_member_latest_version' => $response->info_member->member_latest_version,
                'm_claim_id' => $response->info_claim->claim_id,
                'm_claim_latest_version' => $response->info_claim->claim_latest_version,
                'm_claim_file_id' => $response->info_claim->claim_file_id,
                'm_claim_file_latest_version' => $response->info_claim->claim_file_latest_version,
                'have_ca' => 1
            ]);
        }
        $request->session()->flash('status', $response->errorMsg); 
        return redirect('/admin/mfile');
    }

    public function check_all(Request $request)
    {
        $miss_mfile = \App\Claim::whereDoesntHave('LogMfile', function (Builder $query) {
            $query->where('m_errorCode', 0);
        })->whereNotNull('url_file_sorted')->get();
        $token = getTokenMfile();
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'bearer '.$token
        ];
        
        $data_have_mfile = [];
        $data_not_mfile  = [];
        foreach ($miss_mfile as $key => $value) {
            $client = new \GuzzleHttp\Client([
                'headers' => $headers
            ]);
            $body = [
                'mode' => config('constants.mode_mfile'),
                'claim_no' => $value->code_claim_show
            ];
            try {
                $response = $client->request("POST", config('constants.link_mfile').'checkclaimno' , ['form_params'=>$body]);
                $response =  json_decode($response->getBody()->getContents());
                
                if($response->errorCode == 0 ){

                    if($response->is_exist == true){
                        $data_have_mfile[] = [
                            'claim_id' => $value->id,
                            'cl_no' => $value->code_claim_show,
                            'm_errorCode' => "0",
                            'm_errorMsg' => "Check done",
                            'have_ca' => 1
                        ];
                    }else{
                        $data_not_mfile[] = [
                            'claim_id' => $value->id,
                            'cl_no' => $value->code_claim_show,
                            'm_errorCode' => "999",
                            'm_errorMsg' => "Check miss"
                        ];
                    }
                }
            } catch (Exception $e) {
                
            }
            
            
            
        }
        if(!empty($data_have_mfile)){
            \App\LogMfile::insert($data_have_mfile);
        }

        if(!empty($data_not_mfile)){
            \App\LogMfile::insert($data_not_mfile);
        }
        $request->session()->flash('status', "Check thành công"); 
        return redirect('/admin/mfile');
    }

    public function update_all(Request $request)
    {
        $miss_mfile = \App\Claim::whereDoesntHave('LogMfile', function (Builder $query) {
            $query->where('m_errorCode', 0);
        })->with('LogMfile')->whereNotNull('url_file_sorted')->get();
        
        foreach ($miss_mfile as $key => $value) {
            try {
                \App\Http\Controllers\AjaxCommonController::sendMfile($value->id);
            } catch (Exception $e) {
               
            }
        }
        
        return redirect('/admin/mfile');
    }
}
