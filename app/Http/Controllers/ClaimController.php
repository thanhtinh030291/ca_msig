<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Config;
use Storage;
use App\Claim;
use App\ItemOfClaim;
use App\ExportLetter;
use App\User;
use App\Product;
use App\HBS_CL_CLAIM;
use App\HBS_CL_LINE;
use App\LetterTemplate;
use App\Setting;
use App\PaymentHistory;
use Auth;
use App\ReasonReject;
use App\Http\Requests\formClaimRequest;
use App\Http\Requests\sendEtalkRequest;
use App\Http\Requests\InputGOPRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use SimilarText\Finder;
use Carbon\Carbon;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use App\TransactionRoleStatus;
use App\LevelRoleStatus;
use App\RoleChangeStatus;
use App\MANTIS_TEAM;
use App\MANTIS_USER_GROUP;
use App\MANTIS_USER;
use App\HBS_MR_MEMBER;
use PDF;
use App\MANTIS_BUG;
use App\MANTIS_CUSTOM_FIELD_STRING;
use Illuminate\Support\Arr;
use App\HBS_MR_MEMBER_PLAN;
use Hfig\MAPI;
use Hfig\MAPI\OLE\Pear;


class ClaimController extends Controller
{
    //use Authorizable;
    public function __construct()
    {
        
        $this->authorizeResource(Claim::class);
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$claim_type =null)
    {
        $claim_type = $claim_type ? $claim_type : "M";
        $itemPerPage = Config::get('constants.paginator.itemPerPage');
        $id_claim =  $request->code_claim;
        $admin_list = User::getListIncharge();

        $finder = [
            'budget_check' => $request->budget_check,
            'budget' => $request->budget,
            'code_claim' => $request->code_claim,
            'created_user' => $request->created_user,
            'created_at' => $request->created_at,
            'updated_user' => $request->updated_user,
            'updated_at' => $request->updated_at,
            'letter_status' => $request->letter_status,
            'barcode' => $request->barcode,
        ];
        $conditionExport = function ($q){
            $q->select('id','claim_id','status', 'info');
        };
        $conditionHasExport = function ($q) use ($request){
            $q->where('status',$request->letter_status);
        };
        $conditionHasExport_team = function ($q) use ($request){
            $team = $request->team;
            if($team == 'team_i'){
                $array_user = User::whereHas("roles", function($qr){ $qr->where("name", "Claim Independent"); })->get()->pluck('id')->toArray();
                $q->whereIn('created_user', $array_user);
            }else{
                $array_user = MANTIS_USER_GROUP::where('team_id', $team)->pluck('user_id')->toArray();
                $array_user = MANTIS_USER::whereIn('id',$array_user)->pluck('email')->toArray();
                $array_user = User::whereIn('email',$array_user)->pluck('id')->toArray();
                $q->whereIn('created_user', $array_user);
            }
            
        };
        $conditionHasBudget = function ($q) use ($request){
            $budget = explode(";", $request->budget);
            $searchMinRate = data_get($budget, 0 , 0) ;
            $searchMaxRate = data_get($budget, 1 , 9999999999);
            $q->where('apv_amt','>=',$searchMinRate);
            $q->where('apv_amt','<=',$searchMaxRate);
        };

        $datas = Claim::findByParams($finder)->where('claim_type',$claim_type)
        ->with(['export_letter_last' => $conditionExport])->orderBy('updated_at', 'desc');
        $team = $request->team;
        if($team != null){
            
            $datas = $datas->whereHas('export_letter_last', $conditionHasExport_team);
        }
        if($request->letter_status != null){
            $datas = $datas->whereHas('export_letter_last', $conditionHasExport);
        }

        if($request->budget != null && $request->budget_check != null ){
            $datas = $datas->whereHas('export_letter_last', $conditionHasBudget);
        }
        
        if($request->memb_ref_no != null){
            $memb_ref_no = str_pad($request->memb_ref_no, 10, "0", STR_PAD_LEFT);
            $HBS_MR_MEMBER = HBS_MR_MEMBER::where('memb_ref_no',$memb_ref_no)->with('CL_LINE')->get();
            $clam_oids = [];
            foreach ($HBS_MR_MEMBER as $key => $value) {
                $cl = $value->CL_LINE->pluck('clam_oid')->unique();
                foreach ($cl as $key => $value) {
                    array_push($clam_oids, $value);
                }
            }
            $datas->whereIn('code_claim',$clam_oids);
        }

        if($request->prov_name != null){
            $claimoids = HBS_CL_LINE::where('prov_oid', $request->prov_name)->pluck('clam_oid')->unique()->toArray();
            $datas->whereIn('code_claim',$claimoids);
        }

        $datas = $datas->paginate($itemPerPage);
        $list_status = RoleChangeStatus::where('claim_type',$claim_type)->pluck('name','id');
        try {
            $list_team = MANTIS_TEAM::pluck('name','id');
            $list_team['team_i']= 'Team Độc Lập';
        } catch (Exception $e) {
            $list_team = [];
        }
        $finder['prov_name'] = $request->prov_name;
        $finder['team'] = $team;
        $finder['letter_status'] = $request->letter_status;
        $finder['memb_ref_no'] = $request->memb_ref_no ? str_pad($request->memb_ref_no, 10, "0", STR_PAD_LEFT) : $request->memb_ref_no;
        if ($claim_type == 'P'){
            return view('claimGOPManagement.index', compact('finder', 'datas', 'admin_list', 'list_status'));
        }else{
            return view('claimManagement.index', compact('finder', 'datas', 'admin_list', 'list_status', 'list_team', 'team'));
        }
        
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($claim_type =null)
    {
        $claim_type = $claim_type ? $claim_type : "M";
        $listReasonReject = ReasonReject::orderBy('id', 'desc')->pluck('name', 'id');
        if ($claim_type == 'P'){
            return view('claimGOPManagement.create', compact('listReasonReject'));
        }else{
            return view('claimManagement.create', compact('listReasonReject'));
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(formClaimRequest $request)
    {
        $claim_type = $request->claim_type;
        //validate
        $issue = MANTIS_BUG::where('id',(int)$request->barcode)->first();
        if($issue == null){
            $request->session()->flash('errorStatus', 'Không Tồn tại Barcode này , vui lòng kiểm tra lại HBS');
            return $claim_type == "P" ? redirect('/admin/P/claim/create')->withInput() : redirect('/admin/claim/create')->withInput() ;
        }
        $id_project_mb = DB::connection('mysql_mantis')->table('mantis_project_table')->where('name','like','%Mobile%')->first()->id;
        
        
        //end valid
        if ($request->_url_file_sorted) {
            saveFile($request->_url_file_sorted[0], config('constants.sortedClaimUpload'));
        }
        $file = $request->file;
        $dataNew = $request->except(['file','file2','table2_parameters', 'table1_parameters']);
        if($issue->project_id == $id_project_mb){
            $dataNew['project'] = 'mobile';
        }
        $user = Auth::User();
        $userId = $user->id;
        $dirUpload = Config::get('constants.formClaimUpload');
        
        // store file
        if($file){
            $imageName = Claim::storeFile($file, $dirUpload);
            $dataNew += ['url_file'  =>  $imageName];
        }
        $dataNew += [
            'mantis_id' => $request->barcode,
            'created_user' =>  $userId,
            'updated_user' =>  $userId,
        ];
        if($user->hasRole('ClaimGOP')){
            $dataNew['claim_type'] = "P";
        }
        $dataItems = [];
        // get value item orc

        if( $request->_row){
            $fieldSelect =  array_flip(array_filter($request->_column));
            $rowData = $request->_row;
            array_shift_assoc($rowData);
            $rowCheck = $request->_checkbox;
            $reason = $request->_selectReason;
            foreach ($rowData as $key => $value) {
                $dataItems[] = new ItemOfClaim([
                    'content' => data_get($value, $fieldSelect['content'], ""),
                    'amount' => data_get($value, $fieldSelect['amount'], 0),
                    'reason_reject_id' => data_get($reason, $key),
                    'parameters' => data_get($request->table1_parameters, $key),
                    'created_user' => $userId,
                    'updated_user' => $userId,
                ]);
            }
        }
        // GET value add item
        if($request->_content){
            $rowContent = $request->_content;
            $rowAmount = $request->_amount;
            $reasonInject = $request->_reasonInject;
            foreach ($rowContent as $key => $value) {
                $dataItems[] = new ItemOfClaim([
                    'content' => $value,
                    'amount' => data_get($rowAmount, $key, 0),
                    'reason_reject_id' => data_get($reasonInject, $key),
                    'parameters' => data_get($request->table2_parameters, $key),true,
                    'created_user' => $userId,
                    'updated_user' => $userId,
                ]);
            }
        }
        
        if ($request->_url_file_sorted) {
            $dataNew['url_file_sorted'] = saveFile($request->_url_file_sorted[0], config('constants.sortedClaimUpload'));
        }
        
        try {
            DB::beginTransaction();
            
            $claim = Claim::create($dataNew);
            $claim->item_of_claim()->saveMany($dataItems);
            
            DB::commit();
            $request->session()->flash('status', __('message.add_claim'));
            return redirect('/admin/claim/'.$claim->id);
        } catch (Exception $e) {
            Log::error(generateLogMsg($e));
            DB::rollback();
            $request->session()->flash('errorStatus', __('message.update_fail'));
            return $claim_type == "P" ? redirect('/admin/P/claim/create')->withInput() : redirect('/admin/claim/create')->withInput() ;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Claim $claim)
    {  
        $claim_type = $claim->claim_type ?  $claim->claim_type : "M";
        $data = $claim;
        //get CSR file
        $CsrFile = $claim->CsrFile;
        $user = Auth::user();
        $admin_list = User::getListIncharge();
        $dirStorage = Config::get('constants.formClaimStorage');
        $dataImage =  $dirStorage . $data->url_file ;
        $listReasonReject = ReasonReject::orderBy('id', 'desc')->pluck('name', 'id');
        $items = $data->item_of_claim;
        $listLetterTemplate = LetterTemplate::where('claim_type',$claim_type)->pluck('name', 'id');
        $HBS_CL_CLAIM = HBS_CL_CLAIM::IOPDiag()->findOrFail($claim->code_claim);
        $role_id = $user->roles->pluck('id')->toArray();
        $RoleChangeStatus = RoleChangeStatus::all();
        $list_status_full = TransactionRoleStatus::all();
        $list_level = LevelRoleStatus::where('claim_type',$claim_type)->get();
        $list_status_ad = RoleChangeStatus::pluck('name','id');
        $adminFee = $HBS_CL_CLAIM->adminFee;
        $export_letter = $data->export_letter;
        $user = Auth::User();
        $IS_FREEZED = 0;
        
        foreach ($export_letter as $key => $value) {
            if($value->letter_template->level != 0){
                $level = $list_level
                ->where('id','=', $value->letter_template->level)
                ->first();
            }else{
                $level = $list_level
                ->where('min_amount','<=', removeFormatPrice(data_get($value->info, 'approve_amt')) )
                ->where('max_amount','>', removeFormatPrice(data_get($value->info, 'approve_amt') ) )
                ->first();
            }
            if($claim->jetcase == 1 && $user->hasRole('QC')){
                $curren_status = $value->status == 0 ? $level->begin_status : $value->status ;
                $list_status =  $list_status_full
                                ->whereIn('role', $role_id)
                                ->where('level_role_status_id', $level->id)
                                ->where('current_status', 4)
                                ->pluck('to_status');
                $list_status = $RoleChangeStatus->whereIn('id' , $list_status)->pluck('name','id');
                $export_letter[$key]['list_status'] = $list_status;
            }elseif(!in_array(1,$role_id)){
                $curren_status = $value->status == 0 ? $level->begin_status : $value->status ;
                $list_status =  $list_status_full
                                ->whereIn('role', $role_id)
                                ->where('level_role_status_id', $level->id)
                                ->where('current_status', $curren_status)
                                ->pluck('to_status');
                $list_status = $RoleChangeStatus->whereIn('id' , $list_status)->pluck('name','id');
                $export_letter[$key]['list_status'] = $list_status;
            }else{
                $curren_status = $value->status == 0 ? $level->begin_status : $value->status ;
                $export_letter[$key]['list_status'] = $list_status_ad;
            }
            $user_create = User::findOrFail($value->created_user);
            if($claim->jetcase == 1){
                $export_letter[$key]['end_status'] = 10;
            }else{
                $export_letter[$key]['end_status'] = $level->end_status;
            }
            if($curren_status ==  $export_letter[$key]['end_status'] && !$user->hasRole('Admin')){
                $export_letter[$key]['list_status'] = collect([]);
            }
        }
        
        try {
            
            $payment_history_cps = json_decode(AjaxCommonController::getPaymentHistoryCPS($data->code_claim_show)->getContent(),true);
            $inv_nos = $HBS_CL_CLAIM->InvNo;
            $payment_history = data_get($payment_history_cps,'data_full',[]);
            $approve_amt = data_get($payment_history_cps,'approve_amt');
            $present_amt = data_get($payment_history_cps,'present_amt');
            $payment_method = $claim_type == "P" ? "TT" :  str_replace("CL_PAYMENT_METHOD_","",data_get($payment_history_cps,'payment_method'));
            $pocy_no = data_get($payment_history_cps,'pocy_ref_no');
            $memb_no = data_get($payment_history_cps,'memb_ref_no');
            $member_name = data_get($payment_history_cps,'member_name');
            $email = data_get($payment_history_cps,'email');
            $hr_email = data_get($payment_history_cps,'hr_email');
            $balance_cps = json_decode(AjaxCommonController::getBalanceCPS($data->clClaim->member->mbr_no , $data->code_claim_show)->getContent(),true);
            $balance_cps = collect(data_get($balance_cps, 'data_full'));
            $tranfer_amt = (int)$approve_amt - (int)collect($payment_history)->sum('TF_AMT')-$balance_cps->sum('DEBT_BALANCE') + (int)$adminFee;
            $tranfer_amt = $claim->include_admin_fee == 1 ? $tranfer_amt : ($tranfer_amt - (int)$adminFee);
            
        } catch (\Throwable $th) {
            $payment_history = [];
            $approve_amt = 0;
            $tranfer_amt = 0;
            $present_amt = 0;
            $member_name = "";
            $pocy_no = "";
            $memb_no = "";
            $payment_method = "";
            $balance_cps = collect([]);
            $inv_nos = null;
            $email = "";
            $hr_email = "";
        }
         //show notication mobile
         $btn_notication = false;
         $renderMessageInvoice = "";
         if($data->project == 'mobile') {
             $btn_notication = true;
             $renderMessageInvoice = renderMessageInvoice($data->id);
         }
         //show btn payment 
        $can_pay_rq = false;
        $count_ap = $export_letter->where('apv_amt',$approve_amt)->where('approve',"!=",null)->count();
        $ready_to_pay_id = \App\MANTIS_CUSTOM_FIELD::where('name','Pay Claim')->first()->id;
        $client_approved_id = \App\MANTIS_CUSTOM_FIELD::where('name','Client Approved')->first()->id;
        $ready_to_pay = \App\MANTIS_CUSTOM_FIELD_STRING::where('bug_id',$claim->barcode)->where('field_id',$ready_to_pay_id)->where('value','Yes')->first();
        $ready_to_pay2 = \App\MANTIS_CUSTOM_FIELD_STRING::where('bug_id',$claim->barcode)->where('field_id',$client_approved_id)->where(function ($query) {
            $query->where('value','Yes')
            ->orWhere('value', 'Client Timeout');
        })->first();
        if($count_ap > 0 && $ready_to_pay != null ){
            $can_pay_rq = true;
        }
        $manager_gop_accept_pay = 'error';
        $hospital_request = $claim->hospital_request;
        $list_diagnosis = $claim->hospital_request ? collect($claim->hospital_request->diagnosis)->pluck('text', 'id') : [];
        $selected_diagnosis = $claim->hospital_request ? collect($claim->hospital_request->diagnosis)->pluck('id') : null;
        if($claim_type == "P"){
            $fromEmail = $claim->inbox_email ? $claim->inbox_email->from . "," . implode(",", $claim->inbox_email->to) : "";
        }else{
            
            if($claim_type == "P"){
                $fromEmail = $claim->inbox_email ? $claim->inbox_email->from . "," . implode(",", $claim->inbox_email->to) : "";
            }else{
                if($pocy_no == 'B 20098520 HPS'){
                    $fromEmail = $email .",anh.phan@mercermarshbenefits.com,ngoc.le@mercermarshbenefits.com,hcmpc@vn.msig-asia.com,yen_lethihai@vn.msig-asia.com,msigclaims@pacificcross.com.vn,cskh.msig@pacificcross.com.vn".",$hr_email";
                }elseif(in_array($pocy_no, ['20098171', '20097793', '20097827' ])){
                    $fromEmail = $email .",banca@vn.msig-asia.com,quan_tranminh2@vn.msig-asia.com,hcmpc@vn.msig-asia.com,yen_lethihai@vn.msig-asia.com,msigclaims@pacificcross.com.vn,cskh.msig@pacificcross.com.vn".",$hr_email";
                }else{
                    $fromEmail = $email .",msigclaims@pacificcross.com.vn,cskh.msig@pacificcross.com.vn".",$hr_email";
                }
            }
        }
        
        $reject_code = collect($claim->RejectCode)->flatten(1)->values()->all();
        $compact = compact(['data', 'dataImage', 'items', 'admin_list', 'listReasonReject', 
        'listLetterTemplate' , 'list_status_ad', 'user', 'payment_history', 'approve_amt','tranfer_amt','present_amt',
        'payment_method','pocy_no','memb_no', 'member_name', 'balance_cps', 'can_pay_rq',
        'CsrFile','manager_gop_accept_pay','hospital_request', 'list_diagnosis', 'selected_diagnosis', 'fromEmail','reject_code','IS_FREEZED','adminFee','inv_nos','btn_notication','renderMessageInvoice'
        ]);
        if ($claim_type == 'P'){
            return view('claimGOPManagement.show', $compact);
        }else{
            return view('claimManagement.show', $compact);
        }
    }

    public function uploadSortedFile(Request $request, $id){
        $claim = Claim::findOrFail($id);
        if ($request->_url_file_sorted) {
            $dataUpdate['url_file_sorted'] = saveFile($request->_url_file_sorted[0], config('constants.sortedClaimUpload'),$claim->url_file_sorted);
            Claim::updateOrCreate(['id' => $id], $dataUpdate);
        }
        return redirect('/admin/claim/'.$id);
    }

    public function uploadSortedFileGOP(Request $request, $id){
        $claim = Claim::findOrFail($id);
        $HBS_CL_CLAIM = HBS_CL_CLAIM::findOrFail($claim->code_claim);
        $user = Auth::User();
        if ($request->_url_file_sorted) {
            $dataUpdate['url_file_sorted'] = saveFile($request->_url_file_sorted[0], config('constants.sortedClaimUpload'),$claim->url_file_sorted);
            Claim::updateOrCreate(['id' => $id], $dataUpdate);
            $request2 = new Request([
                'user' => $claim->created_user,
                'content' => 'Hồ sơ gốc của claim '.$claim->code_claim_show.' được cập nhật bởi '.$user->name.' Vui lòng kiểm tra lại thông tin tại : 
                <a href="'.route('claim.show',$claim->id).'">'.route('claim.show',$claim->id).'</a>'
            ]);
            $send_mes = new SendMessageController();
            $send_mes->sendMessage($request2);
            $body = [
                'user_email' => $user->email,
                'issue_id' => $claim->barcode,
                'text_note' => " Dear Team, \n Đính kèm là hồ sơ GOP. \n Thanks,",
            ];
            $handle = fopen(storage_path("app/public/sortedClaim/{$dataUpdate['url_file_sorted']}"),'r');
            $treamfile = stream_get_contents($handle);
            fclose($handle);
            $body['files'] = [
                [
                    'name' => "hosogop.pdf",
                    "content" => base64_encode($treamfile)
                ]
                ];
            try {
                    $res = PostApiMantic('api/rest/plugins/apimanagement/issues/add_note_reply_letter/files', $body);
                    $res = json_decode($res->getBody(),true);
            } catch (Exception $e) {
        
                $request->session()->flash(
                    'errorStatus', 
                    generateLogMsg($e)
                );
                return redirect('/admin/claim/'.$id)->withInput();
            }

        }
        $claim->report_admin()->updateOrCreate([],
            [   
                'MEMB_NAME' => $HBS_CL_CLAIM->MemberNameCap,
                'POCY_REF_NO' => $HBS_CL_CLAIM->police->pocy_ref_no,
                'MEMB_REF_NO' => $HBS_CL_CLAIM->member->memb_ref_no,
                'PRES_AMT' => $HBS_CL_CLAIM->sumPresAmt,
                'INV_NO' => $HBS_CL_CLAIM->invNo,
                'PROV_NAME' => $HBS_CL_CLAIM->FirstLine->prov_name,
                'RECEIVE_DATE' => Carbon::now()->toDateString(),
                'created_user' => $user->id,
                'updated_user' => $user->id,
                'CL_NO' => $claim->code_claim_show, 
            ]
        );
        $request->session()->flash('status', 'Đã Cập nhật hồ sơ GOP Thành Công');
        return redirect('/admin/claim/'.$id);
    }

    public function barcode_link($barcode)
    { 
        $barcode = str_pad($barcode,7,"0",STR_PAD_LEFT);
        $claim = Claim::where('barcode', $barcode)->first();
        if($claim){
            return redirect("admin/claim/{$claim->id}");
        }else{
            echo 'Đường dẫn chưa hợp lệ.';
        }
        
    }

    public function addNote(Request $request){
        $dataUpdate = [ 
            'note_id' => $request->note_id
        ];
        ExportLetter::updateOrCreate(['id' => $request->id], $dataUpdate);
        $request->session()->flash('status', __('message.update_claim'));
        return redirect('/admin/claim/'.$request->claim_id)->withInput();
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Claim $claim)
    {
        $data = $claim;
        $user = Auth::user();
        $listReasonReject = ReasonReject::orderBy('id', 'desc')->pluck('name', 'id');
        $dirStorage = Config::get('constants.formClaimStorage');
        $dataImage = [];
        $previewConfig = [];
        if($data->url_file){
            $dataImage[] = "<img class='kv-preview-data file-preview-image' src='" . asset('images/csv.png') . "'>";
            $previewConfig[]['caption'] = $data->url_file;
            $previewConfig[]['width'] = "120px";
            $previewConfig[]['url'] = "/admin/tours/removeImage";
            $previewConfig[]['key'] = $data->url_file;
        }
        $listCodeClaim = HBS_CL_CLAIM::where('clam_oid', $data->code_claim)->limit(20)->pluck('cl_no', 'clam_oid');
        
        //dd($data->item_of_claim->pluck('content'));
        return view('claimManagement.edit', compact(['data', 'dataImage', 'previewConfig', 'listReasonReject', 'listCodeClaim']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(formClaimRequest $request, Claim $claim)
    {

        $data = $claim;
        $userId = Auth::User()->id;
        $dataUpdate = $request;
        $dataUpdate = $dataUpdate->except(['table2_parameters']);
        $issue = MANTIS_BUG::where('id',(int)$request->barcode)->first();
        if($issue == null){
            $request->session()->flash('errorStatus', 'Không Tồn tại Barcode này , vui lòng kiểm tra lại HBS');
            return redirect('/admin/claim/'.$data->id.'/edit')->withInput();
        }
        $id_project_mb = DB::connection('mysql_mantis')->table('mantis_project_table')->where('name','like','%Mobile%')->first()->id;
        if($issue->project_id == $id_project_mb){
            $dataUpdate['project'] = 'mobile';
        }
        if ($request->_url_file_sorted) {
            $dataUpdate['url_file_sorted'] = saveFile($request->_url_file_sorted[0], config('constants.sortedClaimUpload'),$claim->url_file_sorted);
        }
        try {
            DB::beginTransaction();
            if (Claim::updateOrCreate(['id' => $claim->id], $dataUpdate)) {
                if ($request->_content != null) {
                    $dataItemNew = [];
                    foreach ($request->_idItem as $key => $value) {
                        if ($value == null) {
                            $dataItemNew[] = [
                                'claim_id' => $claim->id,
                                'reason_reject_id' => $request->_reasonInject[$key],
                                'content' => $request->_content[$key],
                                'amount' => $request->_amount[$key],
                                'parameters' => data_get($request->table2_parameters, $key),
                                'created_user' => $userId,
                                'updated_user' => $userId,
                            ];
                        } else {
                            $keynew = $key - 1;
                            $data->item_of_claim[$keynew]->updated_user = $userId;
                            $data->item_of_claim[$keynew]->reason_reject_id = $request->_reasonInject[$key];
                            $data->item_of_claim[$keynew]->content = $request->_content[$key];
                            $data->item_of_claim[$keynew]->parameters = data_get($request->table2_parameters, $key);
                            $data->item_of_claim[$keynew]->amount = $request->_amount[$key];
                        }
                    }
                     //delete
                    $dataDel = ItemOfClaim::whereNotIn('id', array_filter($request->_idItem))->where('claim_id', $data->id);
                    $dataDel->delete();
                    // update
                    $data->push();
                    // new season price
                    $data->item_of_claim()->createMany($dataItemNew);
                } else {
                    $dataDel = ItemOfClaim::where('claim_id', $data->id);
                    $dataDel->delete();
                } // update and create new tour_set
                DB::commit();
                $request->session()->flash('status', __('message.update_claim'));
            }
            return redirect('/admin/claim/'.$data->id);
        } catch (Exception $e) {
            Log::error(generateLogMsg($e));
            DB::rollback();
            $request->session()->flash(
                'errorStatus', 
                __('message.update_fail')
            );
            return redirect('/admin/claim/'.$data->id.'/edit')->withInput();
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Claim $claim)
    {
        $data = $claim;
        $claim_type = $claim->claim_type ?  $claim->claim_type : "M";
        $dirUpload = Config::get('constants.formClaimUpload');
        Storage::delete($dirUpload . $data->url_file);
        $data->item_of_claim()->delete();
        $data->delete();
        if ($claim_type == 'P'){
            return redirect('/admin/P/claim')->with('status', __('message.delete_claim'));
        }else{
            return redirect('/admin/claim')->with('status', __('message.delete_claim'));
        }
    }
    // change
    public function getLevel($export_letter, $list_level, $claim_type = 'M')
    {
        
        if($export_letter->letter_template->level != 0){
            $level = $list_level
            ->where('id','=', $export_letter->letter_template->level)
            ->first();
        }else{
            $level = $list_level
            ->where('min_amount','<=', removeFormatPrice(data_get($export_letter->info, 'approve_amt')) )
            ->where('max_amount','>', removeFormatPrice(data_get($export_letter->info, 'approve_amt')) )
            ->where('claim_type', $claim_type)
            ->first();
        }
        return $level;
    }
    // wait for check
    public function waitCheck(Request $request)
    {
        
        $claim_id = $request->claim_id;
        $claim  = Claim::itemClaimReject()->findOrFail($claim_id);
        $claim_type = $claim->claim_type;
        //validate
            $now = Carbon::now()->toDateTimeString();
            $HBS_CL_CLAIM = HBS_CL_CLAIM::findOrFail($claim->code_claim);
            if($claim_type == "P"){
                $prov_oid = $HBS_CL_CLAIM->FirstLine->prov_oid;
                $pocy_ref_no = $HBS_CL_CLAIM->police->pocy_ref_no;

                if($pocy_ref_no){
                    $PocyManagement = \App\PocyManagement::where('pocy_ref_no',$pocy_ref_no)->first();
                    if($PocyManagement != null){
                        $list_p = explode(",",$PocyManagement->providers);
                        if(in_array($prov_oid, $list_p)){
                            return redirect('/admin/claim/'.$claim_id)->with('errorStatus', 'PROVIDER này không được GOP , vui lòng liên hệ Manager GOP .');
                        }
                    }
                }
            }
            
            
            $issue = MANTIS_BUG::where('id',$claim->barcode)->first();
            if($issue == null){
                return redirect('/admin/claim/'.$claim_id)->with('errorStatus', 'Không Tồn tại Barcode này , vui lòng kiểm tra lại HBS');
            }
            
            // $count_provider_not = $HBS_CL_CLAIM->HBS_CL_LINE->whereIn('prov_oid',config('constants.not_provider'))->count();
            // if($count_provider_not > 0){
            //     return redirect('/admin/claim/'.$claim_id)->with('errorStatus', 'Tồn tại provider: "BUMRUNGRAD INTERNATIONAL HOSPITAL" vui lòng cập nhật lại HBS ');
            // }
            // $memb_ref_no = $HBS_CL_CLAIM->member->memb_ref_no;
            // $all_memb_oid = HBS_MR_MEMBER::where('memb_ref_no', $memb_ref_no)->pluck('memb_oid')->toArray();
            // $conditionPLB = function ($q) {
            //     $q->with(['PD_BEN_HEAD']);
            // };
            // $conditionMPPB = function ($q) use ($conditionPLB){
            //     $q->with(['PD_PLAN_BENEFIT' => $conditionPLB]);
            //     $q->where('ben_type_ind','Y');
            // };
            // $conditionMPL = function ($q) use($conditionMPPB){
            //     $q->with(['MR_POLICY_PLAN_BENEFIT' => $conditionMPPB]);
            // };
            // $HBS_MR_MEMBER_PLAN = HBS_MR_MEMBER_PLAN::whereIn('memb_oid',$all_memb_oid)
            // ->where('MR_MEMBER_PLAN.EFF_DATE','<=',$now)
            // ->where('MR_MEMBER_PLAN.EXP_DATE','>=',$now)
            // ->with(['MR_POLICY_PLAN' => $conditionMPL])
            // ->where('status', null)
            // ->where('term_date',null)->get();
            // if($HBS_MR_MEMBER_PLAN->count() > 1){
            //     $all_pl = [];
            //     foreach ($HBS_MR_MEMBER_PLAN as $key => $value) {
            //         $tyles_bn = $value->MR_POLICY_PLAN->MR_POLICY_PLAN_BENEFIT->pluck('PD_PLAN_BENEFIT.PD_BEN_HEAD.scma_oid_ben_type');
            //         foreach ($tyles_bn as $key2 => $value2) {
            //             $all_pl[] = $value2;
            //         }
                    
            //     }
            //     if( count($all_pl) != count(array_unique($all_pl))){
            //         return redirect('/admin/claim/'.$claim_id)->with('errorStatus', 'Tồn tại đồng thời Plan trùng nhau Vui lòng báo NB Terminate ');
            //     }
            // }
        //end Validate
        $claim->touch();
        $id = $request->id;
        $user = Auth::User();
        $export_letter = ExportLetter::findOrFail($id);
        $user_create = User::findOrFail($export_letter->created_user);
        $wail = [];

        if($export_letter->note == null){
            $data = [];
        }else{
            $data = $export_letter->note;
        }

        if ($request->save_letter == 'save'){
            $create_user_sign = getUserSignThumb_create($export_letter->created_user);
            //$create_user_sign = $user_create->name;
            $export_letter->wait = [  'user' => $user->id,
                'created_at' => Carbon::now()->toDateTimeString(),
                'data' => str_replace('[[$per_creater_sign]]', $create_user_sign, $request->template)
            ];
            $HBS_CL_CLAIM = HBS_CL_CLAIM::IOPDiag()->findOrFail($claim->code_claim);
            $approve_amt = (int)$HBS_CL_CLAIM->sumAppAmt;
            $export_letter->info = ['approve_amt' => $approve_amt ,
                                    'PCV_EXPENSE' => data_get($export_letter->info,'PCV_EXPENSE') , 
                                    "DEBT_BALANCE" => data_get($export_letter->info,'DEBT_BALANCE') ];
            $export_letter->apv_amt = preg_replace('/[^0-9]/', "", $approve_amt);
            
            

        }else{
            $status_change = $request->status_change;
            $status_change = explode("-",$status_change);
            $to_user = [];
            if($status_change[1] == 'rejected'){
                array_push($data ,
                [  'user' => $user->id,
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'data' => $request->template
                ]);
                $export_letter->note = $data;
                $export_letter->approve = null;
            }
            if($status_change[1] == 'approved'){ //nofice && update mantis customfield

                $vip = $HBS_CL_CLAIM->member->mbr_vip == "YN_Y" ? 'Yes' : 'No';
                $SumPresAmt =  $HBS_CL_CLAIM->SumPresAmt ;
                $SumOrgPresAmt =  $HBS_CL_CLAIM->SumOrgPresAmt ;
                $currency =  $SumPresAmt == $SumOrgPresAmt ? 'VND' : 'USD';
                $SumAppAmt = $HBS_CL_CLAIM->SumAppAmt;
                $mantis_id = (int)$HBS_CL_CLAIM->barcode;
                $body = [
                    "email" =>  $user_create->email,
                    "vip" =>  $vip,
                    "currency" => $currency,
                    "pres_amt" => $SumPresAmt,
                    "app_amt" => $SumAppAmt
                ];

            
                try {
                    $res = PostApiMantic("api/rest/claim/update/{$mantis_id}", $body,"PATCH");
                    $res = json_decode($res->getBody(),true);
                } catch (Exception $e) {

                    $request->session()->flash(
                        'errorStatus', 
                        generateLogMsg($e)
                    );
                    return redirect('/admin/claim/'.$claim_id)->withInput();
                }
                // if($user->hasRole('Claim')){
                //     $leader = $user->Leader;
                //     if($leader != null){
                //         $to_user = [$leader];
                //     }
                // }
                // if($request->status_change != 26 && ($user->hasRole('Lead') || $user->hasRole('Claim Independent'))){
                //     $to_user = User::whereHas("roles", function($q){ $q->where("name", "QC"); })->get()->pluck('id')->toArray();
                //     $to_user = [Arr::random($to_user)];
                // }

                if($claim_type != "P"){
                    if($user->hasRole('Claim Independent')){
                        $to_user = [$user_create->supper];
                    }
                    
                    if( $user->hasRole('Supper')){
                        $to_user = User::whereHas("roles", function($q){ $q->where("name", "QC"); })->get()->pluck('id')->toArray();
                        $to_user = [Arr::random($to_user)];
                    }
                    if( $user->hasRole('QC') && removeFormatPrice(data_get($export_letter->info, 'approve_amt')) >= 10000000){
                        $to_user = [$user_create->manager];
                    }
                    
                    if( $user->hasRole('Manager') &&  removeFormatPrice(data_get($export_letter->info, 'approve_amt')) >= 50000000){
                        $to_user = [$user_create->header];
                    }
                }

                
                // Claim GOP
                if ($claim_type == "P") {
                    if ($user->hasRole('ClaimGOP')) {
                        $to_user = User::whereHas("roles", function($q){ $q->where("name", "Supper"); })->get()->pluck('id')->toArray();
                        $to_user = [Arr::random($to_user)];
                    }
                    if ($user->hasRole('Supper') && removeFormatPrice(data_get($export_letter->info, 'approve_amt')) > 10000000) {
                        $to_user = Setting::findOrFail(1)->manager_gop_claim;
                    }
                    if ($user->hasRole('ManagerGOP') &&  removeFormatPrice(data_get($export_letter->info, 'approve_amt')) > 50000000) {
                        $to_user = Setting::findOrFail(1)->header_claim;
                    }
                }

                if(!empty($to_user)){
                    foreach ($to_user as $key => $value) {
                        $request2 = new Request([
                            'user' => $value,
                            'content' => 'Letter của claim '.$claim->code_claim_show.' yêu cầu tiến hành xác nhận bởi '.$user->name.' Vui lòng kiểm tra lại thông tin tại : 
                            <a href="'.route('claim.show',$claim_id).'">'.route('claim.show',$claim_id).'</a>'
                        ]);
                        $send_mes = new SendMessageController();
                        $send_mes->sendMessage($request2);
                    }
                    
                }
            }
            
            $export_letter->status = $status_change[0];
            $list_level = LevelRoleStatus::all();
            $level = $this->getLevel($export_letter, $list_level, $claim->claim_type);
            if($claim->jetcase == 1 && $user->hasRole('QC')){
                $per_approve_sign_replace = $claim_type == "P" ? getUserSignThumb() : getUserSign();
                $approve_user_sign = $user->name;
                if($export_letter->letter_template->letter_payment == null){
                    $export_letter->approve = [  'user' => $user->id,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'data' => str_replace(['[[$per_approve_sign]]','[[$per_approve_sign_replace]]'], [$approve_user_sign,$per_approve_sign_replace], data_get($export_letter->wait, "data")),
                    ];
                }else{
                    $export_letter->approve = [  'user' => $user->id,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'data' => str_replace(['[[$per_approve_sign]]','[[$per_approve_sign_replace]]'], [$approve_user_sign,$per_approve_sign_replace], data_get($export_letter->wait, "data")),
                        'data_payment' => base64_encode($this->letterPayment($export_letter->letter_template->letter_payment , $request->claim_id , $id, 1, $user_create->supper)['content'])
                    ];
                    
                }
                //save log approve 
                
                $export_letter->log_hbs_approved()->update([
                    'approve' => json_encode([
                        'user' => $user->id,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'MANTIS_ID' => $claim->barcode,
                        'MEMB_NAME' => $HBS_CL_CLAIM->MemberNameCap,
                        'POCY_REF_NO' =>  $HBS_CL_CLAIM->police->pocy_ref_no,
                        'MEMB_REF_NO' => $HBS_CL_CLAIM->member->memb_ref_no,
                    ])
                ]);

            }elseif($level->signature_accepted_by == $status_change[0] || ($user_create->hasRole('Claim Independent') && $user->hasRole('Manager'))){
                $per_approve_sign_replace = $claim_type == "P" ? getUserSignThumb() : getUserSign();
                $approve_user_sign = $user->name;
                if($export_letter->letter_template->letter_payment == null){
                    $export_letter->approve = [  'user' => $user->id,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'data' => str_replace(['[[$per_approve_sign]]','[[$per_approve_sign_replace]]'], [$approve_user_sign,$per_approve_sign_replace], data_get($export_letter->wait, "data")),
                    ];
                }else{
                    $export_letter->approve = [  'user' => $user->id,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'data' => str_replace(['[[$per_approve_sign]]','[[$per_approve_sign_replace]]'], [$approve_user_sign,$per_approve_sign_replace], data_get($export_letter->wait, "data")),
                        'data_payment' => base64_encode($this->letterPayment($export_letter->letter_template->letter_payment , $request->claim_id , $id, 1)['content'])
                    ];
                    
                }
                //save log approve 
                
                $export_letter->log_hbs_approved()->update([
                    'approve' => json_encode([
                        'user' => $user->id,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'MANTIS_ID' => $claim->barcode,
                        'MEMB_NAME' => $HBS_CL_CLAIM->MemberNameCap,
                        'POCY_REF_NO' =>  $HBS_CL_CLAIM->police->pocy_ref_no,
                        'MEMB_REF_NO' => $HBS_CL_CLAIM->member->memb_ref_no,
                    ])
                ]);
            }elseif($user_create->hasRole('Claim Independent')){
                if($request->status_change == 14 || $request->status_change == 26 ){
                    if($export_letter->letter_template->letter_payment == null){
                        $export_letter->approve = [  'user' => $user->id,
                            'created_at' => Carbon::now()->toDateTimeString(),
                            'data' => data_get($export_letter->wait, "data"),
                        ];
                    }else{
                        $export_letter->approve = [  'user' => $user->id,
                            'created_at' => Carbon::now()->toDateTimeString(),
                            'data' => data_get($export_letter->wait, "data"),
                            'data_payment' => base64_encode($this->letterPayment($export_letter->letter_template->letter_payment , $request->claim_id , $id, 1)['content'])
                        ];
                        
                    }
                    //save log approve 
                    $export_letter->log_hbs_approved()->update([
                        'approve' => json_encode([
                            'user' => $user->id,
                            'created_at' => Carbon::now()->toDateTimeString(),
                            'MANTIS_ID' => $claim->barcode,
                            'MEMB_NAME' => $HBS_CL_CLAIM->MemberNameCap,
                            'POCY_REF_NO' =>  $HBS_CL_CLAIM->police->pocy_ref_no,
                            'MEMB_REF_NO' => $HBS_CL_CLAIM->member->memb_ref_no,
                        ])
                    ]);
                }
            }
            
            if($status_change[1] == 'rejected'){
                $status_notifi = RoleChangeStatus::findOrFail($status_change[0])->name;
                $url_n = route('claim.show',['claim' => $claim_id]);
                $text_notifi = "Claim: <a href='{$url_n}'>{$url_n}</a> đã chuyển sang trạng thái <span class='text-danger font-weight-bold' >{$status_notifi}</span> bởi {$user->name}";
                $arr_id = $export_letter->log->pluck('causer_id')->unique();
                $arr_id = $arr_id->reject(function ($value, $key) use($user){
                    return $value == $user->id;
                });
                $arr_id = $arr_id->toArray();
                notifi_system($text_notifi, $arr_id);
            }else{
                $status_notifi = RoleChangeStatus::findOrFail($status_change[0])->name;
                $url_n = route('claim.show',['claim' => $claim_id]);
                $text_notifi = "Claim: {$claim->code_claim_show} Link <a href='{$url_n}'>{$url_n}</a> đã chuyển sang trạng thái <span class='text-success font-weight-bold' > {$status_notifi}</span> bởi {$user->name}";
                $arr_id = [$user_create->id];
                notifi_system($text_notifi, $arr_id);
            }
        }
        

        $export_letter->save();        
        return redirect('/admin/claim/'.$claim_id)->with('status', __('message.update_claim'));
    }
    // send Etalk 
    public function sendEtalk(Request $request){
        $claim_id = $request->claim_id;
        $barcode = $request->barcode;
        $id = $request->id;
        $export_letter = ExportLetter::findOrFail($id);
        $user = Auth::User();
        $claim  = Claim::itemClaimReject()->findOrFail($claim_id);
        $HBS_CL_CLAIM = HBS_CL_CLAIM::IOPDiag()->findOrFail($claim->code_claim);
        $namefile = Str::slug("{$export_letter->letter_template->name}_{$HBS_CL_CLAIM->memberNameCap}", '-');
        $body = [];
        
        $body = [
            'user_email' => $user->email,
            'issue_id' => $claim->barcode,
            'text_note' => " Dear Team,  \n Claim gửi là thư  '{$export_letter->letter_template->name}'  và chi tiết theo như file đính kèm. \n Thanks,",
        ];
        if($claim->project == 'mobile'){
            $body['text_note'] = "Dear {$HBS_CL_CLAIM->memberNameCap},  \n PCV gửi là thư  '{$export_letter->letter_template->name}'  và chi tiết theo như file đính kèm. \n Thanks,";
        }
            // gop
            $mpdf = null;
            $match_form_gop = preg_match('/(FORM GOP)/', $export_letter->letter_template->name , $matches);
            if($match_form_gop){
                $mpdf = new \Mpdf\Mpdf(['tempDir' => base_path('resources/fonts/'), 'margin_top' => 35]);
                $fileName = storage_path("app/public/sortedClaim")."/". $claim->hospital_request->url_form_request;
                
                $pagesInFile = $mpdf->SetSourceFile($fileName);
    
    
                for ($i = 1; $i <= $pagesInFile; $i++) {
                    $mpdf->AddPage();
                    $tplId = $mpdf->ImportPage($i);
                    $mpdf->UseTemplate($tplId, 1, 1, 200, 285);
                    $mpdf->WriteHTML('<div style="position: absolute; bottom: 0;
                    right:5"><barcode code="'.$claim->barcode.'" type="C93"  height="1.3" />
                    <div style="text-align: center">'.$claim->barcode.'</div></div>');
                    
                }
                $mpdf->AddPage();
                $mpdf->WriteHTML('
                <div style="position: absolute; right: 5px; top: 0px;font-weight: bold; ">
                    <img src="'.asset("images/header.jpg").'" alt="head">
                </div>');
                $mpdf->SetHTMLFooter('
                <div style="text-align: right; font-weight: bold;">
                    <img src="'.asset("images/footer.png").'" alt="foot">
                </div>');
                $mpdf->WriteHTML('<div style="position: absolute; bottom: 3;
                    right:1"><barcode code="'.$claim->barcode.'" type="C93"  height="1.3" />
                    <div style="text-align: center">'.$claim->barcode.'</div></div>');
                $mpdf->WriteHTML(data_get($export_letter->approve, 'data'));
    
            }else{
                if($export_letter->approve != null){
                    
                    $data['content'] = $export_letter->approve['data'];
                }elseif($export_letter->wait != null){
                   
                    $data['content'] = $export_letter->wait['data'];
                }else{
                    $data = $this->letter($export_letter->letter_template->id , $claim_id, $id);
                }
                $mpdf = new \Mpdf\Mpdf(['tempDir' => base_path('resources/fonts/'), 'margin_top' => 34, 'margin_bottom' => 30]);
                $mpdf->WriteHTML('
                <div style="position: absolute; right: 5px; top: 0px;font-weight: bold; ">
                    <img src="'.asset("images/header.jpg").'" alt="head">
                </div>');
                $mpdf->WriteHTML('<div style="position: absolute; bottom: 3;
                    right:1"><barcode code="'.$claim->barcode.'" type="C93"  height="1.3" />
                    <div style="text-align: center">'.$claim->barcode.'</div></div>');
                $mpdf->SetHTMLFooter('
                <div style="text-align: right; font-weight: bold;">
                    <img src="'.asset("images/footer.png").'" alt="foot">
                </div>');
                $mpdf->WriteHTML($data['content']);
            }
            
            $file_contents = base64_encode($mpdf->Output('filename.pdf',\Mpdf\Output\Destination::STRING_RETURN));
            
            $body['files'] = [
                [
                    'name' => $namefile.".pdf",
                    "content" => $file_contents
                ]
                ];
        
        if($export_letter->letter_template->status_mantis != NULL ){
            $body['status_id'] = $export_letter->letter_template->status_mantis;
            $match_form_gop = preg_match('/(FORM GOP)/', $export_letter->letter_template->name , $matches);
            if($match_form_gop){
                if($HBS_CL_CLAIM->SumAppAmt == 0){
                    $body['status_id'] = config('constants.status_mantic_value.declined');
                }
            }
        }
        
        if(preg_match('/(Thư thông báo bồi thường)|(Thư Phúc Đáp)/', $export_letter->letter_template->name , $matchess)){
            
            $diff = $HBS_CL_CLAIM->SumPresAmt - $HBS_CL_CLAIM->SumAppAmt ;
            
            if($HBS_CL_CLAIM->SumAppAmt == 0 ){
                $body['status_id'] = config('constants.status_mantic_value.declined');
            }elseif($diff == 0){
                $body['status_id'] = config('constants.status_mantic_value.accepted');
            }else {
                $body['status_id'] = config('constants.status_mantic_value.partiallyaccepted');
            }
        }
        if(isset($export_letter->approve['data_payment']))
        {
            $body['files'][] = [
                'name' => ''.$namefile."(payment).pdf",
                'content' => $export_letter->approve['data_payment']
            ];
        }
        
        try {
            
            $res = PostApiMantic('api/rest/plugins/apimanagement/issues/add_note_reply_letter/files', $body);
            $res = json_decode($res->getBody(),true);
            if($claim->project == 'mobile'){

                //$lang  = $HBS_CL_CLAIM->member->scma_oid_country_corr_addr == 'COUNTRY_084' ?'vi':'en';
                $lang = 'vi';
                if(in_array(data_get($body,'status_id','99'),array_values(config('constants.status_mantic_value')))){
                    $k_mss = data_get(config('constants.status_mantic'),$body['status_id'],'inforequest');
                    $tran_place = [
                        'name' => $HBS_CL_CLAIM->memberNameCap,
                    ];
                    $dicription = trans("message.$k_mss",$tran_place,$lang);
                    $response = send_message_mobile('Pacific Cross VietNam',$dicription,$claim,$file_contents);
                    if($response->code == 0){
                        $request->session()->flash(
                            'status', 
                           'Đã gửi thông báo Mobile app thành công'
                        );
                    }else{
                        $request->session()->flash(
                            'errorStatus', 
                            'Gửi thông báo Mobile app thất bại --->' . $response->message
                        );
                    }
                }
            }else{
                
                // $file_name_dr =  $namefile."-".time() ;
                // Storage::cloud()->put($file_name_dr.'.pdf',  base64_decode($file_contents));
                // $dir = '/';
                // $recursive = false; // Có lấy file trong các thư mục con không?
                // $contents_dr = collect(Storage::cloud()->listContents($dir, $recursive));
                // $file_dr = $contents_dr
                // ->where('type', '=', 'file')
                // ->where('filename', '=', $file_name_dr)
                
                // ->first();
                // $url_dr = "https://drive.google.com/file/d/". data_get($file_dr,'path');
                // //dd($url_dr);
                
                // dd($url_dr);
            }
            
        } catch (Exception $e) {

            $request->session()->flash(
                'errorStatus', 
                generateLogMsg($e)
            );
            return redirect('/admin/claim/'.$claim_id)->withInput();
        }
        if(data_get($res,'status') == 'success'){
            $data = $export_letter->info;
            $data['notes'] = $res['data']['note'];
            $export_letter->info = $data;
            $export_letter->save();
            if ($export_letter->apv_amt > 0) {
                $pay_time = PaymentHistory::where('CL_NO', $claim->code_claim_show)->count();
                $claim->finish_and_pay()->updateOrCreate([
                    'cl_no' => $claim->code_claim_show,
                    'pay_time' => $pay_time + 1
                ], [
                    'cl_no' => $claim->code_claim_show,
                    'mantis_id' =>  $claim->barcode,
                    'approve_amt' => $export_letter->apv_amt,
                    'finished' => 0,
                    'payed' => 0,
                    'user' => $user->id,
                    'notify' => 1,
                    'pay_time' => $pay_time + 1,
                    'date_tbbt' => preg_match('/(Thư thông báo bồi thường)/', $export_letter->letter_template->name , $matchess) ? Carbon::now()->toDateTimeString() : null,
                ]);
            }
        }
        return redirect('/admin/claim/'.$claim_id)->with('status', __('message.update_claim'));
    }
    
    // confirm contract
    public function confirmContract(Request $request){
        $claim_id = $request->claim_id;
        $id = $request->id;
        $user = Auth::User();
        $claim  = Claim::itemClaimReject()->findOrFail($claim_id);
        $barcode = $claim->barcode;
        $HBS_CL_CLAIM = HBS_CL_CLAIM::IOPDiag()->findOrFail($claim->code_claim);
        $member = $HBS_CL_CLAIM->member;
        $body = [
            'user_email' => $user->email,
            'issue_id' => $barcode,
            'text_note' => $request->message,
        ];
        try {
            $res = PostApiMantic('api/rest/plugins/apimanagement/issues/add_note_reply_letter/files', $body);
            $res = json_decode($res->getBody(),true);
        } catch (Exception $e) {

            $request->session()->flash(
                'errorStatus', 
                generateLogMsg($e)
            );
            return redirect('/admin/claim/'.$claim_id)->withInput();
        }

        return redirect('/admin/claim/'.$claim_id)->with('status', __('message.update_claim'));
    }
    // send summary Etalk
    public function sendSummaryEtalk($id){
        
        $claim = Claim::findOrFail($id);
        if ($claim->url_file_sorted == null){
            $mes = "Không có file ! vui lòng thêm và save lại..";
            $status = 'error';
        }else{
            try {
                $body = [
                    
                    'multipart' =>[
                        [
                            'name' => 'bug_id',
                            'contents' => $claim->barcode
                        ],
                        [
                            'name' => 'p_file_name',
                            'contents' => 'sumary_'.$claim->code_claim_show.Carbon::now()->format('d-m-y').'.pdf'
                        ],
                        [
                            'name'     => 'user_email',
                            'contents' => Auth::User()->email,
                        ],
                        [
                            'name' => 'p_file',
                            'contents' => fopen(storage_path("app/public/sortedClaim/{$claim->url_file_sorted}"),'r')
                        ]
                    ]
                ];
                
                $res = PostApiManticHasFile('api/rest/plugins/apimanagement/issues/upload_sumary/files', $body);
                $statusCode =  $res->getStatusCode();
                $mes = $res->getReasonPhrase();
                $status = 'success';
            } catch (Exception $e) {
                $mes = $e->getMessage();
                $status = 'error';
            }
        }
        return response()->json(['status' => $status , 'message' => $mes]);
    }
    public function searchFullText(Request $request)
    {
            $res = ['status' => 'error'];
        if ($request->search != '') {
            $list = Product::pluck('name');
            $finder = new Finder($request->search, $list);
            $nameFirst = $finder->first();
            if(isset($nameFirst)){
                similar_text($request->search , $nameFirst, $percent);
                if($percent >= Config::get('constants.percentSelect')){
                    $res = ['status' => 'success', 'data' => ['name' => $nameFirst , 'percent' => round($percent, 0) ]];
                }
            }
        }
        return response()->json($res, 200); 
    }

    public function searchFullText2(Request $request)
    {
        $res = ['status' => 'error'];
        if ($request->search != '') {
            $data = Product::where('name', 'LIKE',"%{$request->search}%")->pluck('name')->toArray();
            if(isset($data)){
                $res = ['status' => 'success', 'data' => $data];
            }
        }
        return response()->json($res, 200);
    }

    public function template(Request $request)
    {
        $res = ['status' => 'error'];
        if ($request->search != '') {
            $data = ReasonReject::findOrFail($request->search);
            if(isset($data)){
                $res = ['status' => 'success', 'data' => $data->template];
            }
        }
        return response()->json($res, 200);
    }

    //request Letter
    public function requestLetter(Request $request){
        
        $userId = Auth::User()->id;
        $claim = Claim::findOrfail($request->claim_id);
        $data_cps = json_decode(json_encode(AjaxCommonController::getPaymentHistoryCPS($claim->code_claim_show),true),true);
        
        $data = [
            'claim_id' => $request->claim_id,
            'letter_template_id' => $request->letter_template_id,
            'status' => config('constants.statusExport.new'),
            'created_user' => $userId,
            'updated_user' => $userId,
            'info' => ['approve_amt' => $request->apv_hbs , 'PCV_EXPENSE' => $request->PCV_EXPENSE , "DEBT_BALANCE" => $request->DEBT_BALANCE ],
            'data_cps' => data_get($data_cps,'original.data'),
            'apv_amt' => preg_replace('/[^0-9]/', "", $request->apv_hbs),
        ] ;
        ExportLetter::create($data);
        return redirect('/admin/claim/'. $request->claim_id )->with('Status', 'Letter was successfully created');
    }

    public function exportLetter(Request $request){
        $export_letter = ExportLetter::findOrFail($request->export_letter_id);
        $claim  = Claim::itemClaimReject()->findOrFail($request->claim_id);
        $user_create = User::findOrFail($export_letter->created_user);
        if($export_letter->approve != null){
            $letter = LetterTemplate::findOrFail($request->letter_template_id);
            $HBS_CL_CLAIM = HBS_CL_CLAIM::IOPDiag()->findOrFail($claim->code_claim);
            $namefile = Str::slug("{$letter->name}_{$HBS_CL_CLAIM->memberNameCap}", '-');
            $data['namefile'] = $namefile;
            $data['content'] = $export_letter->approve['data'];
        }elseif($export_letter->wait != null){
            $letter = LetterTemplate::findOrFail($request->letter_template_id);
            $HBS_CL_CLAIM = HBS_CL_CLAIM::IOPDiag()->findOrFail($claim->code_claim);
            $namefile = Str::slug("{$letter->name}_{$HBS_CL_CLAIM->memberNameCap}", '-');
            $data['namefile'] = $namefile;
            $data['content'] = $export_letter->wait['data'];
        }else{
            $data = $this->letter($request->letter_template_id , $request->claim_id, $request->export_letter_id);
        }
        
       
            $data['content'] = "<html><body>".$data['content']."</body></html>";
            $create_user_sign = getUserSignThumb_create($export_letter->created_user);
            //$create_user_sign = $user_create->name;
            $data['content'] = str_replace('[[$per_creater_sign]]', $create_user_sign, $data['content']);
            $data['content'] = str_replace('[[$per_approve_sign]]', "", $data['content']);
            $mpdf = new \Mpdf\Mpdf(['tempDir' => base_path('resources/fonts/'), 'margin_top' => 225, 'margin_left' => 22]);
            
            $match_form_gop = preg_match('/(FORM GOP)/', $export_letter->letter_template->name , $matches);
            $match_gop = preg_match('/GOP/', $export_letter->letter_template->name , $matches_g);
            if($match_form_gop){
                $mpdf = new \Mpdf\Mpdf(['tempDir' => base_path('resources/fonts/'), 'margin_top' => 35]);
                $fileName = storage_path("app/public/sortedClaim")."/". $claim->hospital_request->url_form_request;
                
                $pagesInFile = $mpdf->SetSourceFile($fileName);
    
    
                for ($i = 1; $i <= $pagesInFile; $i++) {
                    $mpdf->AddPage();
                    $tplId = $mpdf->ImportPage($i);
                    $mpdf->UseTemplate($tplId, 1, 1, 200, 285);
                    $mpdf->WriteHTML('<div style="position: absolute; bottom: 0;
                    right:5"><barcode code="'.$claim->barcode.'" type="C93"  height="1.3" />
                    <div style="text-align: center">'.$claim->barcode.'</div></div>');
                    
                }
                $mpdf->AddPage();
                $mpdf->WriteHTML('
                <div style="position: absolute; right: 5px; top: 0px;font-weight: bold; ">
                    <img src="'.asset("images/header.jpg").'" alt="head">
                </div>');
                $mpdf->SetHTMLFooter('
                <div style="text-align: right; font-weight: bold;">
                    <img src="'.asset("images/footer.png").'" alt="foot">
                </div>');
                $mpdf->WriteHTML('<div style="position: absolute; bottom: 3;
                    right:1"><barcode code="'.$claim->barcode.'" type="C93"  height="1.3" />
                    <div style="text-align: center">'.$claim->barcode.'</div></div>');
                $mpdf->WriteHTML($data['content']);
    
            }elseif($match_gop){
                $mpdf = new \Mpdf\Mpdf(['tempDir' => base_path('resources/fonts/')]);
                $mpdf->WriteHTML('
                <div style="position: absolute; right: 5px; top: 0px;font-weight: bold; ">
                    <img src="'.asset("images/header.jpg").'" alt="head">
                </div>');
                $mpdf->SetHTMLFooter('
                <div style="text-align: right; font-weight: bold;">
                    <img src="'.asset("images/footer.png").'" alt="foot">
                </div>');
                $mpdf->WriteHTML($data['content']);
            }else{
                $mpdf = new \Mpdf\Mpdf(['tempDir' => base_path('resources/fonts/'), 'margin_top' => 32, 'margin_bottom' => 30]);
                $mpdf->WriteHTML('
                <div style="position: absolute; right: 5px; top: 0px;font-weight: bold; ">
                    <img src="'.asset("images/header.jpg").'" alt="head">
                </div>');
                $mpdf->SetHTMLFooter('
                <div style="text-align: right; font-weight: bold;">
                    <img src="'.asset("images/footer.png").'" alt="foot">
                </div>');
                $mpdf->WriteHTML($data['content']);
            }
            
            header("Content-Type: application/pdf");
            header("Expires: 0");//no-cache
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");//no-cache
            header("content-disposition: attachment;filename={$data['namefile']}.pdf");
            echo $mpdf->Output($data['namefile'].'.pdf',\Mpdf\Output\Destination::STRING_RETURN);
        
    }

    public function exportLetterPDF(Request $request){
        $export_letter = ExportLetter::findOrFail($request->export_letter_id);
        if($export_letter->approve != null){
            $letter = LetterTemplate::findOrFail($request->letter_template_id);
            $claim  = Claim::itemClaimReject()->findOrFail($request->claim_id);
            $HBS_CL_CLAIM = HBS_CL_CLAIM::IOPDiag()->findOrFail($claim->code_claim);
            $namefile = Str::slug("{$letter->name}_{$HBS_CL_CLAIM->memberNameCap}", '-');
            $data['content'] =  base64_decode($export_letter->approve['data_payment']);
            $data['namefile'] = $namefile;
        }else{
            $data = $this->letterPayment($request->letter_template_id , $request->claim_id , $request->export_letter_id);
        }
        
        header("Content-Type: application/pdf");
        header("Expires: 0");//no-cache
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");//no-cache
        header("content-disposition: attachment;filename={$data['namefile']}.pdf");
        echo $data['content'];
    }

    //ajax 
    public function previewLetter(Request $request){
        
        $data = $this->letter($request->letter_template_id , $request->claim_id , $request->export_letter_id);
        return response()->json(mb_convert_encoding($data['content'], 'UTF-8', 'UTF-8'));
    }

    // leter_payment
    public function letterPayment($letter_template_id , $claim_id ,$export_letter_id, $approve = null , $id_user_sign = null){
        
        $data = $this->letter($letter_template_id , $claim_id,  $export_letter_id);
        $export_letter = ExportLetter::findOrFail($export_letter_id);
        $user_create = User::findOrFail($export_letter->created_user);
        $claim  = Claim::findOrFail($claim_id);
        $claim_type = $claim->claim_type;
        $create_user_sign = getUserSignThumb_create($export_letter->created_user);
        //$create_user_sign = $user_create->name;
        $data['content'] = str_replace('[[$per_creater_sign]]', $create_user_sign, $data['content']);

        if($approve != null){
            $user = Auth::user();
            $per_approve_sign_replace = $claim_type == "P" ? getUserSignThumb() : getUserSign();
            $approve_user_sign = $user->name;
            $data['content'] = str_replace(['[[$per_approve_sign]]','[[$per_approve_sign_replace]]'], [$approve_user_sign,$per_approve_sign_replace], $data['content']);
        }else{
            $data['content'] = str_replace('[[$per_approve_sign]]', "", $data['content']);
        }
        

        $mpdf = new \Mpdf\Mpdf(['tempDir' => base_path('resources/fonts/')]);
        $mpdf->WriteHTML('
        <div style="text-align: right; font-weight: bold; ">
            <img src="'.asset("images/header.jpg").'" alt="head">
        </div>');
        $mpdf->SetHTMLFooter('
        <div style="text-align: right; font-weight: bold;">
            <img src="'.asset("images/footer.png").'" alt="foot">
        </div>');
        $mpdf->WriteHTML( 
            '<div style="padding-top: 20px">'
            .$data['content'].
            '</div>'
        );      
        return ['content' => $mpdf->Output('filename.pdf',\Mpdf\Output\Destination::STRING_RETURN) , 'namefile' => $data['namefile']];
    }
    
    // export letter
    public function letter($letter_template_id , $claim_id ,$export_letter_id = null){
        $letter = LetterTemplate::findOrFail($letter_template_id);
        $claim  = Claim::itemClaimReject()->findOrFail($claim_id);
        $HBS_CL_CLAIM = HBS_CL_CLAIM::IOPDiag()->findOrFail($claim->code_claim);
        $namefile = Str::slug("{$letter->name}_{$HBS_CL_CLAIM->memberNameCap}", '-');
        $benefitOfClaim = benefitOfClaim($HBS_CL_CLAIM);
        $police = $HBS_CL_CLAIM->Police;
        $policyHolder = $HBS_CL_CLAIM->policyHolder;
        $payMethod = payMethod($HBS_CL_CLAIM);
        $payMethod_en = payMethod($HBS_CL_CLAIM,"en");
        $barcode = '<barcode code="'.$claim->barcode.'" type="C93"  height="1.3" />'
        .'<p style="text-align: right;">'.$claim->barcode.'</p>';
        $CSRRemark_TermRemark = CSRRemark_TermRemark($claim);
        $CSRRemark_TermRemark_en = CSRRemark_TermRemark($claim, 'en');
        $plan = $HBS_CL_CLAIM->plan;
        $CSR_REMASK_ALL_LINE = $HBS_CL_CLAIM->remark;
        $CSRRemark = $CSRRemark_TermRemark['CSRRemark'];
        $CSRRemark_en = $CSRRemark_TermRemark_en['CSRRemark'];
        $TermRemark = $CSRRemark_TermRemark['TermRemark'];
        $TermRemark_en = $CSRRemark_TermRemark_en['TermRemark'];
        $itemsReject = $CSRRemark_TermRemark['itemsReject'];
        $sumAmountReject = $CSRRemark_TermRemark['sumAmountReject'];
        $sumAppAmt = (int)$HBS_CL_CLAIM->sumAppAmt ;
        $adminfee = $claim->include_admin_fee == 1 ? $HBS_CL_CLAIM->adminFee : 0 ;
        $export_letter = ExportLetter::findOrFail($export_letter_id);
        $note_pay =  note_pay($export_letter);
        if($export_letter->data_cps == null || $export_letter->data_cps == [] ){
            $time_pay = formatPrice($sumAppAmt + $adminfee);
            $paymentAmt = $time_pay;
        }else{
            $time_pay = [];
            $sum_tf_amt = 0;
            foreach ($export_letter->data_cps as $key => $value) {
                    $time_pay[] = "Thanh toán lần {$value['PAYMENT_TIME']}: " . formatPrice($value['TF_AMT']);
                    $sum_tf_amt += $value['TF_AMT'];
                
            };
            if(collect($export_letter->data_cps)->sum('TF_AMT') != $sumAppAmt){
                $time_pay[] = 'Thanh toán bổ sung: ' . formatPrice($sumAppAmt + $adminfee - $sum_tf_amt);
            }
            $time_pay[] = 'Tổng Cộng: '.formatPrice($sumAppAmt + $adminfee);
            $time_pay = implode("<br>",$time_pay);
            $paymentAmt = $sumAppAmt + $adminfee - $sum_tf_amt;
        }
        $Provider = $HBS_CL_CLAIM->FirstLine;
        
        $prov_address = array_filter([
            $Provider->addr1,
            $Provider->addr2,
            $Provider->addr3,
            $Provider->addr4,
        ]);
        $claimHistory = $this->claimHistory($HBS_CL_CLAIM);
        $claimHistory_en = $this->claimHistory($HBS_CL_CLAIM,'en');
        $incurDateTo = Carbon::parse($HBS_CL_CLAIM->FirstLine->incur_date_to);
        $incurDateFrom = Carbon::parse($HBS_CL_CLAIM->FirstLine->incur_date_from);
        $OPGOP = $HBS_CL_CLAIM->HBS_CL_LINE->whereIn('PD_BEN_HEAD.ben_head',['DENT','OVRX'])->sum('app_amt');
        $SURGOP = $HBS_CL_CLAIM->HBS_CL_LINE->whereIn('PD_BEN_HEAD.ben_head',['SUR','OSUR','OPR','ANES'])->sum('app_amt');
        $ICUGOP = $HBS_CL_CLAIM->HBS_CL_LINE->whereIn('PD_BEN_HEAD.ben_head',['ICU','CCU'])->sum('app_amt');
        $MATGOP = $HBS_CL_CLAIM->HBS_CL_LINE->whereIn('PD_BEN_HEAD.ben_head',['MAT','DELI','NBC'])->sum('app_amt');
        $OTHERGOP = $HBS_CL_CLAIM->HBS_CL_LINE->whereNotIn('PD_BEN_HEAD.ben_head',['DENT','OVRX','SUR','OSUR','OPR','ANES','ICU','CCU','MAT','DELI','NBC'])->sum('app_amt');
        $ProApvAmt = data_get($claim->hospital_request,'prov_gop_pres_amt',0) - $sumAmountReject;
        $typeGOP = typeGop(data_get($claim->hospital_request,'type_gop',0));
        $noteGOP = data_get($claim->hospital_request,'note',"");
        $total_incur_hbs = $incurDateTo->diffInDays($incurDateFrom) == 0 ? "1 " : $incurDateTo->diffInDays($incurDateFrom) . "";
        $diffIncur = data_get($claim->hospital_request,'incur_time',null) ?  data_get($claim->hospital_request,'incur_time') : $total_incur_hbs ;
        $incurDateTo = data_get($claim->hospital_request,'incur_to',null) ?  data_get($claim->hospital_request,'incur_to') : $incurDateTo->format('d/m/Y') ;
        $incurDateFrom = data_get($claim->hospital_request,'incur_from',null) ?  data_get($claim->hospital_request,'incur_from') : $incurDateFrom->format('d/m/Y') ;
        $Diagnosis = data_get($claim->hospital_request,'diagnosis',null) ?  data_get($claim->hospital_request,'diagnosis') : $HBS_CL_CLAIM->FirstLine->RT_DIAGNOSIS->diag_desc;
        $diffIncur_extb = data_get($claim->hospital_request,'incur_time_extb',null) ?  "/".data_get($claim->hospital_request,'incur_time_extb') : "" ;
        $deniedAmt = $HBS_CL_CLAIM->sumPresAmt - (int)$sumAppAmt;
        $content = $letter->template;
        $content = str_replace('[[$clNo]]', $claim->code_claim_show , $content);
        $content = str_replace('[[$claimHistory]]', $claimHistory , $content);
        $content = str_replace('[[$claimHistory_en]]', $claimHistory_en , $content);
        $content = str_replace('[[$memEffDate]]', Carbon::parse($HBS_CL_CLAIM->member->eff_date)->format('d/m/Y') , $content);
        $content = str_replace('[[$ProvPstAmt]]', formatPrice(data_get($claim->hospital_request,'prov_gop_pres_amt')), $content);
        $content = str_replace('[[$ProDeniedAmt]]', formatPrice($sumAmountReject), $content);
        $content = str_replace('[[$ProvName]]', $Provider->prov_name, $content);
        $content = str_replace('[[$bankNameProv]]', $Provider->bank_name, $content);
        $content = str_replace('[[$bankAddressProv]]', $Provider->bank_branch, $content);
        $content = str_replace('[[$acctNoProv]]', $Provider->acct_no, $content);
        $content = str_replace('[[$payeeProv]]', $Provider->payee, $content);
        $content = str_replace('[[$ProAddress]]', implode(",",$prov_address), $content);
        $content = str_replace('[[$Diagnosis]]', $Diagnosis, $content);
        $content = str_replace('[[$incurDateTo]]',$incurDateTo, $content);
        $content = str_replace('[[$incurDateFrom]]', $incurDateFrom, $content);
        $content = str_replace('[[$diffIncur]]', $diffIncur , $content);
        $content = str_replace('[[$diffIncur_extb]]', $diffIncur_extb , $content);
        $content = str_replace('[[$CSR_REMASK_ALL_LINE]]', $CSR_REMASK_ALL_LINE , $content);
        $content = str_replace('[[$OPGOP]]', formatPrice(round($OPGOP)), $content);
        $content = str_replace('[[$SURGOP]]', formatPrice(round($SURGOP)), $content);
        $content = str_replace('[[$ICUGOP]]', formatPrice(round($ICUGOP)), $content);
        $content = str_replace('[[$MATGOP]]', formatPrice(round($MATGOP)), $content);
        $content = str_replace('[[$OTHERGOP]]', formatPrice(round($OTHERGOP)), $content);
        $content = str_replace('[[$ProApvAmt]]', formatPrice(round($ProApvAmt)), $content);
        $content = str_replace('[[$itemsReject]]', implode(",",$itemsReject), $content);
        $content = str_replace('[[$typeGOP]]', $typeGOP, $content);
        $content = str_replace('[[$noteGOP]]', $noteGOP, $content);
        $content = str_replace('[[$barcode]]', $barcode, $content);  
        $content = str_replace('[[$note_pay]]', $note_pay, $content);
        $content = str_replace('[[$applicantName]]', $HBS_CL_CLAIM->applicantName, $content);
        $content = str_replace('[[$benefitOfClaim]]', $benefitOfClaim , $content);
        $content = str_replace('[[$IOPDiag]]', IOPDiag($HBS_CL_CLAIM, $claim_id) , $content);
        $content = str_replace('[[$IOPDiag_en]]', IOPDiag($HBS_CL_CLAIM, $claim_id, 'en') , $content);
        $content = str_replace('[[$PRefNo]]', $police->pocy_ref_no, $content);
        $content = str_replace('[[$PhName]]', $policyHolder->poho_name_1 ." ". $policyHolder->poho_name_2, $content);
        $content = str_replace('[[$memberNameCap]]', $HBS_CL_CLAIM->memberNameCap, $content);
        $content = str_replace('[[$ltrDate]]', getVNLetterDate(), $content);
        $content = str_replace('[[$nowDay]]', Carbon::now()->format('d/m/Y'), $content);
        $content = str_replace('[[$pstAmt]]', formatPrice($HBS_CL_CLAIM->sumPresAmt), $content);
        $content = str_replace('[[$deniedAmt]]', formatPrice($deniedAmt) , $content);
        $content = str_replace('[[$claimNo]]', $claim->code_claim_show , $content);
        $content = str_replace('[[$memRefNo]]', $HBS_CL_CLAIM->member->memb_ref_no , $content);
        $content = str_replace('[[$memNo]]', $HBS_CL_CLAIM->member->mbr_no , $content);
        $content = str_replace('[[$DOB]]', Carbon::parse($HBS_CL_CLAIM->member->dob)->format('d/m/Y') , $content);
        $content = str_replace('[[$SEX]]', str_replace('SEX_', "",$HBS_CL_CLAIM->member->scma_oid_sex) , $content);
        $content = str_replace('[[$PoNo]]', $police->pocy_no, $content);
        $content = str_replace('[[$EffDate]]', Carbon::parse($police->eff_date)->format('d/m/Y'), $content);
        $content = str_replace('[[$now]]', datepayment(), $content);
        $head_sign = "<span><img src='".asset('images/chukymoc.jpg')."' alt='face'></img></span>";
        $content = str_replace('[[$HEADSIGN]]', $head_sign , $content);
        $htm_infoReject = "";
        $htm_infoReject_en = "";
        if ($deniedAmt != 0 || $CSRRemark) {
            $htm_infoReject = "<p><span style='font-family: arial, helvetica, sans-serif; font-size: 11pt;'>
            Số tiền không được bồi thường:  <strong style='font-family: arial, helvetica, sans-serif; font-size: 11pt;'>".formatPrice($deniedAmt). " VNĐ</strong>" .
            "</span></p>" . 
            "<p><span style='font-family: arial, helvetica, sans-serif; font-size: 11pt;'>Diễn giải:</span></p>" .
            implode('', $CSRRemark) .
            "<p><span style='font-family: arial, helvetica, sans-serif; font-size: 11pt;'>Quý khách vui lòng tham khảo (các) điều khoản sau:</span></p>" .
            implode('', $TermRemark);

            $htm_infoReject_en = "<p><span style='font-family: arial, helvetica, sans-serif; font-size: 11pt;'>
            Rejected amount:  <strong style='font-family: arial, helvetica, sans-serif; font-size: 11pt;'>".formatPrice($deniedAmt). " VND</strong>" .
            "</span></p>" . 
            "<p><span style='font-family: arial, helvetica, sans-serif; font-size: 11pt;'>Description:</span></p>" .
            implode('', $CSRRemark_en) .
            "<p><span style='font-family: arial, helvetica, sans-serif; font-size: 11pt;'>Please kindly refer to the below condition(s):</span></p>" .
            implode('', $TermRemark_en);
        }
        $content = str_replace('[[$infoReject]]', $htm_infoReject , $content);
        $content = str_replace('[[$infoReject_en]]', $htm_infoReject_en , $content);
        if((int)$sumAppAmt > 0){
            $content = str_replace('[[$payMethod]]', $payMethod, $content);
            $content = str_replace('[[$payMethod_en]]', $payMethod_en, $content);
        }else{
            $content = str_replace('[[$payMethod]]', "", $content);
            $content = str_replace('[[$payMethod_en]]', "", $content);
        }
        

        $content = str_replace('[[$invoicePatient]]', implode(" ",$HBS_CL_CLAIM->HBS_CL_LINE->pluck('inv_no')->toArray()) , $content);
        if($CSRRemark){
            $content = str_replace('[[$CSRRemark]]', implode('',$CSRRemark) , $content);
            $content = str_replace('[[$TermRemark]]', implode('',array_unique($TermRemark)) , $content);
        }
        $content = str_replace('[[$tableInfoPayment]]', $this->tableInfoPayment($HBS_CL_CLAIM) , $content);
        $content = str_replace('[[$tableInfoPayment_en]]', $this->tableInfoPayment($HBS_CL_CLAIM,'en') , $content);
        $content = str_replace('[[$apvAmt]]', formatPrice((int)$sumAppAmt), $content);
        $content = str_replace('[[$time_pay]]', $time_pay, $content);
        $content = str_replace('[[$adminfee]]', formatPrice((int)$adminfee), $content);
        $content = str_replace('[[$paymentAmt]]', formatPrice($paymentAmt), $content);
        return ['content' => $content , 'namefile' => $namefile];
        
    }

    function tableInfoPayment($HBS_CL_CLAIM , $lang = null){
        $sum_pre_amt = 0;
        $sum_app_amt = 0;
        if($lang == 'en'){
            $HbsBenhead = \App\HbsBenhead::pluck('desc_en','code');
        }else{
            $HbsBenhead = \App\HbsBenhead::pluck('desc_vn','code');
        }
        
        $col_benefit = $lang == 'en' ? 'Benefits' : 'Quyền lợi bảo hiểm';
        $col_submitted_amount = $lang == 'en' ? 'Submitted amount<br>(in VND)' : 'Số tiền yêu cầu bồi thường <br> (bằng VNĐ)';
        $col_paid_amount = $lang == 'en' ? 'Paid amount<br>(Based on validity documents)<br>(in VND)' : 'Số tiền bồi thường<br>(Căn cứ trên chứng từ hợp lệ)<br>(bằng VNĐ)';
        $html = '
        <style type="text/css">
            table { page-break-inside:auto ; font-size: 11pt; font-family: arial, helvetica, sans-serif;}
            tr    { page-break-inside:avoid; page-break-after:auto ; font-size: 11pt; font-family: arial, helvetica, sans-serif;}
            thead { display:table-header-group ; font-size: 11pt; font-family: arial, helvetica, sans-serif;}
            tfoot { display:table-footer-group ; font-size: 11pt; font-family: arial, helvetica, sans-serif;}
        </style>
                <table style=" border: 1px solid black; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="border: 1px solid black ; font-family: arial, helvetica, sans-serif ; font-size: 11pt">'.$col_benefit.'</th>
                            <th style="border: 1px solid black ; font-family: arial, helvetica, sans-serif ; font-size: 11pt">'.$col_submitted_amount.'</th>
                            <th style="border: 1px solid black ; font-family: arial, helvetica, sans-serif ; font-size: 11pt">'.$col_paid_amount.'</th>
                        </tr>
                    <thead>';
        $IP = [];
        $OP = [];
        $DT = [];
        foreach ($HBS_CL_CLAIM->HBS_CL_LINE as $keyCL_LINE => $valueCL_LINE) {
            switch ($valueCL_LINE->PD_BEN_HEAD->scma_oid_ben_type) {
                case 'BENEFIT_TYPE_DT':
                    $DT[] = $valueCL_LINE;
                    break;
                case 'BENEFIT_TYPE_OP':
                    $OP[] = $valueCL_LINE;
                    break;
                default:
                    $dateic = Carbon::parse($valueCL_LINE->incur_date_from)->format('dmy');
                    $hopital = Str::slug($valueCL_LINE->prov_name,'-');
                    $IP["$dateic-$hopital"][] = $valueCL_LINE;
                    break;
            }
        }
        $html .= '<tbody>';
            // nội trú
        foreach ($IP as $keyIP => $valueIP) {
            $html .= '<tr>
                    <td style="border: 1px solid black; font-weight:bold; font-family: arial, helvetica, sans-serif ; font-size: 11pt">'. ($lang == 'en' ? 'Inpatient': 'Nội Trú') .'</td>
                    <td style="border: 1px solid black; font-family: arial, helvetica, sans-serif ; font-size: 11pt"></td>
                    <td style="border: 1px solid black; font-family: arial, helvetica, sans-serif ; font-size: 11pt"></td>
                </tr>';
            foreach ($valueIP as $key => $value) {
                $range_pay = "";
                
                $html .=    '
                            <tr>
                                <td style="border: 1px solid black ; font-family: arial, helvetica, sans-serif ; font-size: 11pt">'.data_get($HbsBenhead,$value->PD_BEN_HEAD->ben_head).'</td>
                                <td style="border: 1px solid black ; font-family: arial, helvetica, sans-serif ; font-size: 11pt ; text-align: center; vertical-align: middle;">'.formatPrice($value->pres_amt).'</td>
                                <td style="border: 1px solid black ; text-align: center; vertical-align: middle; font-family: arial, helvetica, sans-serif ; font-size: 11pt">'.formatPrice($value->app_amt).'</td>
                            </tr>
                            ';
                $sum_pre_amt += $value->pres_amt;
                $sum_app_amt += $value->app_amt;
            }
        }
        // ngoại trú
        foreach ($OP as $key => $value) {
            
            if($key == 0){
                
                $html .= '<tr>
                            <td style="border: 1px solid black ; font-weight:bold; font-family: arial, helvetica, sans-serif ; font-size: 11pt">'.($lang == 'en' ? 'Outpatient' : 'Ngoại Trú').'</td>
                            <td style="border: 1px solid black ; font-family: arial, helvetica, sans-serif ; font-size: 11pt"></td>
                            <td style="border: 1px solid black ; font-family: arial, helvetica, sans-serif ; font-size: 11pt"></td>
                        </tr>';
            }
            $html .=    '<tr>
                            <td style="border: 1px solid black ;font-family: arial, helvetica, sans-serif ; font-size: 11pt">'.data_get($HbsBenhead,$value->PD_BEN_HEAD->ben_head).'</td>
                            <td style="border: 1px solid black; text-align: center; vertical-align: middle; font-family: arial, helvetica, sans-serif ; font-size: 11pt">'.formatPrice($value->pres_amt).'</td>
                            <td style="border: 1px solid black; text-align: center; vertical-align: middle; font-family: arial, helvetica, sans-serif ; font-size: 11pt">'.formatPrice($value->app_amt).'</td>
                        </tr>';
            $sum_pre_amt += $value->pres_amt;
            $sum_app_amt += $value->app_amt;
        }

        // rang
        foreach ($DT as $key => $value) {
            if($key == 0){
                
                $html .= '<tr>
                            <td style="border: 1px solid black ; font-weight:bold; font-family: arial, helvetica, sans-serif ; font-size: 11pt">'.($lang == 'en' ? 'Dental' : 'Răng').'</td>
                            <td style="border: 1px solid black ; font-family: arial, helvetica, sans-serif ; font-size: 11pt"></td>
                            <td style="border: 1px solid black ; font-family: arial, helvetica, sans-serif ; font-size: 11pt"></td>
                        </tr>';
            }
            $html .=    '<tr>
                            <td style="border: 1px solid black ; font-family: arial, helvetica, sans-serif ; font-size: 11pt">'.data_get($HbsBenhead,$value->PD_BEN_HEAD->ben_head).'</td>
                            <td style="border: 1px solid black; text-align: center; vertical-align: middle; font-family: arial, helvetica, sans-serif ; font-size: 11pt">'.formatPrice($value->pres_amt).'</td>
                            <td style="border: 1px solid black; text-align: center; vertical-align: middle; font-family: arial, helvetica, sans-serif ; font-size: 11pt">'.formatPrice($value->app_amt).'</td>
                        </tr>';
            $sum_pre_amt += $value->pres_amt;
            $sum_app_amt += $value->app_amt;
        }
            $html .=    '<tr>
                            <th style="border: 1px solid black ;font-family: arial, helvetica, sans-serif ; font-size: 11pt" >'.($lang == 'en' ? 'Total: ': 'Tổng cộng: ').'</th>
                            
                            <th style="border: 1px solid black ; font-family: arial, helvetica, sans-serif ; font-size: 11pt">'.formatPrice($sum_pre_amt).'</th>
                            <th style="border: 1px solid black ; font-family: arial, helvetica, sans-serif ; font-size: 11pt">[[$time_pay]]</th>
                        </tr>';

        $html .= '</tbody>';
        $html .= '</table>';
        return $html;
    }

    function getlimitOP($value){
        $ben_head = $value->PD_BEN_HEAD->ben_head;
        $data= [];
        $data['amt_from'] = 0;
        if ($value->MR_POLICY_PLAN) {
            foreach ($value->MR_POLICY_PLAN->PD_PLAN->PD_PLAN_LIMIT as $keyt => $valuet) {
                if ($valuet->limit_type == 'T') {
                    $array  = $valuet->PD_BEN_HEAD->where('scma_oid_ben_type', 'BENEFIT_TYPE_OP');
                    if ($array->count() > 0) {
                        $data['amt_yr'] = $valuet->amt_yr == null ? 0 : $valuet->amt_yr;
                    }
                }
                
                if ($ben_head == 'OVRX' || $ben_head == 'OV' || $ben_head == 'RX' || $ben_head == 'LAB' || $ben_head == 'XRAY' || $ben_head == 'PHYS' || $ben_head == 'CHIR') {
                    if ($valuet->limit_type == 'H') {
                        $array  = $valuet->PD_BEN_HEAD->where('scma_oid_ben_type', 'BENEFIT_TYPE_OP')->where('ben_head', 'OVRX');
                        if ($array->count() > 0) {
                            $data['amt_from'] = $valuet->deduct_amt_vis == null ? 0 :  $valuet->deduct_amt_vis;
                            $data['amt_to'] = $valuet->amt_vis == null ? 0 :  $valuet->amt_vis;
                        }
                    }
                } else {
                    if ($valuet->limit_type == 'CH') {
                        $array  = $valuet->PD_BEN_HEAD->where('scma_oid_ben_type', 'BENEFIT_TYPE_OP')->whereIn('ben_head', ['ACUP', 'BSET', 'CGP', 'CMED', 'HERB', 'HLIS', 'HMEO', 'HYNO', 'OSTE']);
                        if ($array->count() > 0) {
                            $data['amt_from'] = $data['amt_from'] >= $valuet->amt_yr ? $data['amt_from'] :  $valuet->amt_yr;
                        }
                    }
                }
            }
        }
        return $data;
    }
    
    function getlimitDT($value){
        $ben_head = $value->PD_BEN_HEAD->ben_head;
        $data= [];
        $data['amt_from'] = 0;
        if ($value->MR_POLICY_PLAN) {
            foreach ($value->MR_POLICY_PLAN->PD_PLAN->PD_PLAN_LIMIT as $keyt => $valuet) {
                if ($valuet->limit_type == 'T') {
                    $array  = $valuet->PD_BEN_HEAD->where('scma_oid_ben_type', 'BENEFIT_TYPE_DT');
                    if ($array->count() > 0) {
                        $data['amt_yr'] = $valuet->amt_yr == null ? 0 : $valuet->amt_yr;
                        $data['amt'] = $valuet->amt_vis == null ? 0 : $valuet->amt_vis;
                    }
                }
            }
        }
    
        return $data;
    }

    function getlimitIP($value){
        $ben_head = $value->PD_BEN_HEAD->ben_head;
        $data= [];
        $data['amt'] = 0;
        if ($value->MR_POLICY_PLAN) {
            foreach ($value->MR_POLICY_PLAN->PD_PLAN->PD_PLAN_LIMIT as $keyt => $valuet) {
                if (in_array($ben_head,['ANES','OPR','SUR']) ) {
                    if ($valuet->limit_type == 'CH') {
                        $array  = $valuet->PD_BEN_HEAD->where('scma_oid_ben_type', 'BENEFIT_TYPE_IP')->whereIn('ben_head', ['ANES', 'OPR', 'SUR']);
                        
                        if ($array->count() > 0) {
                            $data['amt'] = $data['amt'] >= $valuet->amt_dis_vis ? $data['amt'] :  $valuet->amt_dis_vis;
                        }
                    }
                }
                if ($ben_head == 'HSP' || $ben_head == 'HVIS' || $ben_head == 'IMIS' || $ben_head == 'POSH' || $ben_head == 'PORX') {
                    if ($valuet->limit_type == 'H') {
                        $array  = $valuet->PD_BEN_HEAD->where('scma_oid_ben_type', 'BENEFIT_TYPE_IP')->where('ben_head', 'IMIS');
                        
                        if ($array->count() > 0) {
                            $data['amt'] =  $valuet->amt_dis_yr;
                        }
                    }
                }

                if (in_array($ben_head,['RB','RB0','IMIS0'])) {
                    if ($valuet->limit_type == 'H') {
                        $array  = $valuet->PD_BEN_HEAD->where('scma_oid_ben_type', 'BENEFIT_TYPE_IP')->where('ben_head', 'RB');
                        
                        if ($array->count() > 0) {
                            $data['amt'] = $valuet->amt_day;
                        }
                    }
                }

                if ($ben_head == 'EXTB') {
                    if ($valuet->limit_type == 'H') {
                        $array  = $valuet->PD_BEN_HEAD->where('scma_oid_ben_type', 'BENEFIT_TYPE_IP')->where('ben_head', 'EXTB');
                        
                        if ($array->count() > 0) {
                            $data['amt'] = $valuet->amt_day;
                        }
                    }
                }

                if ($ben_head == 'HNUR') {
                    if ($valuet->limit_type == 'H') {
                        $array  = $valuet->PD_BEN_HEAD->where('scma_oid_ben_type', 'BENEFIT_TYPE_IP')->where('ben_head', 'HNUR');
                        
                        if ($array->count() > 0) {
                            $data['amt'] = $valuet->amt_day;
                        }
                    }
                }

                if ($ben_head == 'PNUR') {
                    if ($valuet->limit_type == 'H') {
                        $array  = $valuet->PD_BEN_HEAD->where('scma_oid_ben_type', 'BENEFIT_TYPE_IP')->where('ben_head', 'PNUR');
                        
                        if ($array->count() > 0) {
                            $data['amt'] = $valuet->amt_day;
                        }
                    }
                }

                if (in_array($ben_head, ['ICU0','ICU','CCU'])) {
                    if ($valuet->limit_type == 'CH') {
                        $array  = $valuet->PD_BEN_HEAD->where('scma_oid_ben_type', 'BENEFIT_TYPE_IP')->whereIn('ben_head', ['ICU', 'CCU']);
                        
                        if ($array->count() > 0) {
                            $data['amt'] = $data['amt'] >= $valuet->amt_day ? $data['amt'] :  $valuet->amt_day;
                        }
                    }
                }

                if ($ben_head == 'ER') {
                    if ($valuet->limit_type == 'H') {
                        $array  = $valuet->PD_BEN_HEAD->where('scma_oid_ben_type', 'BENEFIT_TYPE_IP')->where('ben_head', 'ER');
                        
                        if ($array->count() > 0) {
                            $data['amt'] = $valuet->amt_dis_yr;
                        }
                    }
                }

                if ($ben_head == 'LAMB') {
                    if ($valuet->limit_type == 'H') {
                        $array  = $valuet->PD_BEN_HEAD->where('scma_oid_ben_type', 'BENEFIT_TYPE_IP')->where('ben_head', 'LAMB');
                        
                        if ($array->count() > 0) {
                            $data['amt'] = $valuet->amt_dis_yr;
                        }
                    }
                }

                if ($ben_head == 'DON' || $ben_head == 'REC') {
                    if ($valuet->limit_type == 'CH') {
                        $array  = $valuet->PD_BEN_HEAD->where('scma_oid_ben_type', 'BENEFIT_TYPE_IP')->whereIn('ben_head', ['DON', 'REC']);
                        
                        if ($array->count() > 0) {
                            $data['amt'] = $data['amt'] >= $valuet->amt_life ? $data['amt'] :  $valuet->amt_life;
                        }
                    }
                }

                if ($ben_head == 'CHEMO' || $ben_head == 'RADIA') {
                    if ($valuet->limit_type == 'CH') {
                        $array  = $valuet->PD_BEN_HEAD->where('scma_oid_ben_type', 'BENEFIT_TYPE_IP')->whereIn('ben_head', ['CHEMO', 'RADIA']);
                        
                        if ($array->count() > 0) {
                            $data['amt'] = $data['amt'] >= $valuet->amt_dis_yr ? $data['amt'] :  $valuet->amt_dis_yr;
                        }
                    }
                }

                if ($ben_head == 'TDAM') {
                    if ($valuet->limit_type == 'H') {
                        $array  = $valuet->PD_BEN_HEAD->where('scma_oid_ben_type', 'BENEFIT_TYPE_IP')->where('ben_head', 'TDAM');
                        
                        if ($array->count() > 0) {
                            $data['amt'] = $valuet->amt_dis_yr;
                        }
                    }
                }
            }
        }  
        return $data;
    }

    public function sendSortedFile(Request $request, $id){
        $path_file = [] ;
        $export_letter_id = $request->export_letter_id;
        $export_letter = ExportLetter::findOrFail($export_letter_id);
        $user = Auth::User();
        $claim  = Claim::itemClaimReject()->findOrFail($id);
        $count_page = 0;
        $claim_type = $claim->claim_type;
        $per_approve_sign_replace = $claim_type == "P" ? getUserSignThumb() : getUserSign();
        //get file 
        if($export_letter->approve != null){
            $data['content_letter'] = str_replace('[[$per_approve_sign_replace]]',$per_approve_sign_replace,$export_letter->approve['data']);
            $data['content_payment'] =  isset($export_letter->approve['data_payment']) ? base64_decode($export_letter->approve['data_payment']) : null;
        }else{
            
            $data['content_letter'] = $export_letter->wait['data'];
            $data['content_payment'] = $export_letter->letter_template->letter_payment ? $this->letterPayment($export_letter->letter_template->letter_payment , $id , $request->export_letter_id) : null;
        }
        // save cache payment
        if($data['content_payment']){
            $file_name_payment =  md5(Str::random(12).time());
            Storage::put('public/cache/' . $file_name_payment, $data['content_payment']);
            $path_file[] = storage_path("app/public/cache/$file_name_payment") ;
            $mpdf = new \Mpdf\Mpdf(['tempDir' => base_path('resources/fonts/')]);
            $count_page += $mpdf->SetSourceFile(storage_path("app/public/cache/$file_name_payment"));
        }
        

        //save cache letter
        $file_name_letter =  md5(Str::random(11).time());
        $mpdf = new \Mpdf\Mpdf(['tempDir' => base_path('resources/fonts/')]);
        $mpdf->WriteHTML( $data['content_letter']);
        if($export_letter->letter_template->name == 'Letter Payment (GOP)'){
            $mpdf->WriteHTML('
                <div style="position: absolute; right: 5px; top: 0px;font-weight: bold; ">
                    <img src="'.asset("images/header.jpg").'" alt="head">
                </div>');
            $mpdf->SetHTMLFooter('
                    <div style="text-align: right; font-weight: bold;">
                        <img src="'.asset("images/footer.png").'" alt="foot">
                    </div>');
        }
        
        $pdf = $mpdf->Output('filename.pdf',\Mpdf\Output\Destination::STRING_RETURN);
        Storage::put('public/cache/' . $file_name_letter, $pdf);
        $path_file[] = storage_path("app/public/cache/$file_name_letter") ;
        $count_page += count($mpdf->pages);
        
        // count number page;
        if($claim->url_file_sorted && file_exists(storage_path('app/public/sortedClaim/'. $claim->url_file_sorted))){
            $filename_sorted = storage_path('app/public/sortedClaim/'. $claim->url_file_sorted);
            $handle = fopen($filename_sorted, "r");
            $file_contents = stream_get_contents($handle);
            fclose($handle);
            if($file_contents != ""){
                $file_name_man =  md5(Str::random(10).time());
                Storage::put('public/cache/' . $file_name_man, $file_contents);
                $path_file[] = storage_path("app/public/cache/$file_name_man") ;
                if($claim->old_number_page_send != 0){
                    
                    $file_name_man_output =  md5(Str::random(9).time());
                    $FirstPage = $claim->old_number_page_send + 1 ;
                    $removed = array_pop($path_file);
                    $cm_run ="gs -sDEVICE=pdfwrite -dNOPAUSE -dQUIET -dBATCH -sOutputFile=". storage_path("app/public/cache/$file_name_man_output") ." ".storage_path("app/public/cache/$file_name_man");
                    exec($cm_run);
                    Storage::delete(str_replace(storage_path("app")."/", "", $removed));
                    $path_file[] = storage_path("app/public/cache/$file_name_man_output");
                }
            }
        }else{
            $file_name =  md5(Str::random(13).time());
            Storage::put('public/sortedClaim/' . $file_name .'.pdf', "");
            $claim->url_file_sorted = $file_name .'.pdf';
            $claim->push();
        }
        $cm_run = "gs -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -dPDFSETTINGS=/prepress -sOutputFile=".storage_path("app/public/sortedClaim/{$claim->url_file_sorted}"). 
        " -dBATCH " . implode(" ",$path_file);
        exec($cm_run);

        foreach ($path_file as $key => $value){
            $path_file[$key]  = str_replace(storage_path("app")."/", "", $value);
        }

        $claim->old_number_page_send = $count_page;
        $claim->save();
        Storage::delete($path_file);
        return redirect('/admin/claim/'.$id)->with('status', __('message.update_claim'));
    }

    public function downloadFinishFile(Request $request, $id){
        $path_file = [] ;
        $export_letter_id = $request->export_letter_id;
        $export_letter = ExportLetter::findOrFail($export_letter_id);
        $user = Auth::User();
        $claim  = Claim::itemClaimReject()->findOrFail($id);
        //get file 
        if($export_letter->approve != null){
            $data['content_letter'] = $export_letter->approve['data'];
            $data['content_payment'] =  isset($export_letter->approve['data_payment']) ? base64_decode($export_letter->approve['data_payment']) : null;
        }else{
            
            $data['content_letter'] = $export_letter->wait['data'];
            $data['content_payment'] = $export_letter->letter_template->letter_payment ? $this->letterPayment($export_letter->letter_template->letter_payment , $id , $request->export_letter_id) : null;
        }
        $mpdf = new \Mpdf\Mpdf(['tempDir' => base_path('resources/fonts/')]);
        // save cache payment
        if($data['content_payment']){
            $file_name_payment =  md5(Str::random(12).time());
            Storage::put('public/cache/' . $file_name_payment, $data['content_payment']);
            $path_file[] = storage_path("app/public/cache/$file_name_payment") ;
            $count_page = $mpdf->SetSourceFile(storage_path("app/public/cache/$file_name_payment"));
            for ($i = 1; $i <= $count_page; $i++) {
                $mpdf->AddPage();
                $tplId = $mpdf->ImportPage($i);
                $mpdf->UseTemplate($tplId);
            }
        }

        //save CSR
        // $CsrFile = $claim->CsrFile->where('rpct_oid','CLSETTRPT01_CC')->first();
        // $url_csr = storage_path("../../". config('constants.mount_disk_hbs') . $CsrFile->path . $CsrFile->filename);
        // $count_page = $mpdf->SetSourceFile($url_csr);
        // for ($i = 1; $i <= $count_page; $i++) {
        //     $mpdf->AddPage('L');
        //     $tplId = $mpdf->ImportPage($i);
        //     $mpdf->UseTemplate($tplId);
        // }

        //save cache letter
        $file_name_letter =  md5(Str::random(11).time());
        $mpdf_lt = new \Mpdf\Mpdf(['tempDir' => base_path('resources/fonts/')]);
        $mpdf_lt->WriteHTML( $data['content_letter']);
        $pdf = $mpdf_lt->Output('filename.pdf',\Mpdf\Output\Destination::STRING_RETURN);
        Storage::put('public/cache/' . $file_name_letter, $pdf);
        $path_file[] = storage_path("app/public/cache/$file_name_letter") ;

        $count_page = $mpdf->SetSourceFile(storage_path("app/public/cache/$file_name_letter"));
        for ($i = 1; $i <= $count_page; $i++) {
            $mpdf->AddPage();
            $tplId = $mpdf->ImportPage($i);
            $mpdf->UseTemplate($tplId);
        }
        $HBS_CL_CLAIM = HBS_CL_CLAIM::IOPDiag()->findOrFail($claim->code_claim);
        //$namefile = Str::slug("{$HBS_CL_CLAIM->Police->pocy_ref_no}_{$HBS_CL_CLAIM->memberNameCap}_CSR_{$claim->code_claim_show}", '-').".pdf";
        $namefile = $HBS_CL_CLAIM->Police->pocy_no."-".strtoupper(Str::slug($HBS_CL_CLAIM->memberNameCap," "))."-CSR-".$claim->code_claim_show.".pdf";
        $mpdf->Output($namefile,'D');
    }

    public function sendCSRFile(Request $request, $id){

        $claim  = Claim::itemClaimReject()->findOrFail($id);
        $CsrFile = $claim->CsrFile->where('rpid_oid',$request->rpid_oid)->first();
        $url_csr = storage_path("../../". config('constants.mount_disk_hbs')  . $CsrFile->path . $CsrFile->filename);
        $mpdf = new \Mpdf\Mpdf(['tempDir' => base_path('resources/fonts/')]);
        $pagecount = $mpdf->SetSourceFile(storage_path("../../". config('constants.mount_disk_hbs') . $CsrFile->path . $CsrFile->filename));
        $file_name_cat =  md5(Str::random(14).time());
        $path_file_name_cat = storage_path("app/public/cache/$file_name_cat");
        $cm_run = "gs -sDEVICE=pdfwrite -dNOPAUSE -dQUIET -dBATCH -dFirstPage=1 -dLastPage={$pagecount} -sOutputFile={$path_file_name_cat} {$url_csr}" ;
        exec($cm_run);
        $path_file[] = $path_file_name_cat;
        if($claim->url_file_sorted && file_exists(storage_path('app/public/sortedClaim/'. $claim->url_file_sorted))){
            $filename_sorted = storage_path('app/public/sortedClaim/'. $claim->url_file_sorted);
            $handle = fopen($filename_sorted, "r");
            $file_contents = stream_get_contents($handle);
            fclose($handle);
            if($file_contents != ""){
                $file_name_man =  md5(Str::random(10).time());
                Storage::put('public/cache/' . $file_name_man, $file_contents);
                $path_file[] = storage_path("app/public/cache/$file_name_man") ;
            }
        }else{
            $file_name =  md5(Str::random(13).time());
            Storage::put('public/sortedClaim/' . $file_name .'.pdf', "");
            $claim->url_file_sorted = $file_name .'.pdf';
            $claim->push();
        }
        $cm_run = "gs -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -dPDFSETTINGS=/prepress -sOutputFile=".storage_path("app/public/sortedClaim/{$claim->url_file_sorted}"). 
        " -dBATCH " . implode(" ",$path_file);
        exec($cm_run);
        array_shift($path_file);
        foreach ($path_file as $key => $value){
            $path_file[$key]  = str_replace(storage_path("app")."/", "", $value);
        }
        Storage::delete($path_file);
        return redirect('/admin/claim/'.$id)->with('status', __('message.update_claim'));
    }

    public function deletePage(Request $request, $id){
        $claim  = Claim::itemClaimReject()->findOrFail($id);
        if($claim->url_file_sorted && file_exists(storage_path('app/public/sortedClaim/'. $claim->url_file_sorted))){
            $filename_sorted = storage_path('app/public/sortedClaim/'. $claim->url_file_sorted);
            $patch_file_convert = storage_path('app/public/cache/'. $claim->url_file_sorted);
            $cm_run ="gs -sDEVICE=pdfwrite -dNOPAUSE -dQUIET -dBATCH -sOutputFile=". $patch_file_convert ." ".$filename_sorted;
            exec($cm_run);
            $mpdf = new \Mpdf\Mpdf(['tempDir' => base_path('resources/fonts/')]);
            $pagecount = $mpdf->SetSourceFile($patch_file_convert);
            
            $allpage =  range(1,$pagecount);
            $page_delete = range($request->from , $request->to);
            $result=array_diff($allpage,$page_delete);
            $pageList = implode(",",$result);
            $file_name =  md5(Str::random(13).time());
            Storage::put('public/sortedClaim/' . $file_name .'.pdf', "");
            $path_file_name = storage_path('app/public/sortedClaim/'. $file_name.'.pdf');
            $cm_run = "gs -q -sDEVICE=pdfwrite -dNOPAUSE -dQUIET -dBATCH -sPageList={$pageList} -sOutputFile={$path_file_name} {$patch_file_convert}";
            exec($cm_run);
            Storage::delete('/public/sortedClaim/'.$claim->url_file_sorted);
            Storage::delete('/public/cache/'.$claim->url_file_sorted);
            $claim->url_file_sorted = $file_name .'.pdf';
            $claim->push();
        }else{
            return redirect('/admin/claim/'.$id)->with('errorStatus', "Không tồn Tại file đã sắp xếp");
        }
        return redirect('/admin/claim/'.$id)->with('status', __('message.update_claim'));
    }

    public function setPcvExpense(Request $request, $id){
        $pattern = '/[^0-9]+/';
        $rp = AjaxCommonController::setPcvExpense($request->paym_id, preg_replace($pattern,"",$request->pcv_expense));
        switch (data_get($rp,'code')) {
            case '00':
                return redirect('/admin/claim/'.$id)->with('status', data_get($rp,'description'));
                break;
            case '01':
            case '02':
                return redirect('/admin/claim/'.$id)->with('errorStatus', data_get($rp,'description'));
            default:
                return redirect('/admin/claim/'.$id)->with('errorStatus', 'System error !!! please try again');
                break;
        }
        
    }

    public function sendPayment(Request $request, $id){
        $claim = Claim::findOrFail($id);
        if($claim->original_invoice_no == null &&  $claim->e_invoice_no == null && $claim->converted_invoice_no == null ){
            return redirect('/admin/claim/'.$id)->with('errorStatus', 'Vui Lòng Nhập Thêm số Hóa Đơn Trên ClaimAsstant'); 
        }
        if($claim->original_invoice_no != null &&  $claim->original_invoice_type != "Yes"){
            return redirect('/admin/claim/'.$id)->with('errorStatus', 'Hóa Đơn góc Chưa Đầy Đủ'); 
        }
        if($claim->e_invoice_no != null &&  $claim->e_invoice_type != "Yes"){
            return redirect('/admin/claim/'.$id)->with('errorStatus', 'Hóa Đơn Điện tử Chưa hợp lệ'); 
        }
        if($claim->converted_invoice_no != null &&  $claim->converted_invoice_type != "Yes"){
            return redirect('/admin/claim/'.$id)->with('errorStatus', 'Hóa Chuyển đổi tử Chưa hợp lệ'); 
        }
        $HBS_CL_CLAIM = HBS_CL_CLAIM::HBSData()->findOrFail($claim->code_claim);
        $HBS_CL_LINE = $HBS_CL_CLAIM->HBS_CL_LINE->first();
        if($HBS_CL_LINE->scma_oid_cl_payment_method == null){
            return redirect('/admin/claim/'.$id)->with('errorStatus', 'Claim Chưa có phương thức thanh toán , Vui lòng (Ament -> Update) lại HBS , và thử lại sau'); 
        }
        switch ($HBS_CL_CLAIM->payMethod) {
            case 'CL_PAYMENT_METHOD_TT':
                if($HBS_CL_LINE->bank_name == null || $HBS_CL_LINE->acct_name == null || $HBS_CL_LINE->acct_no == null){
                    return redirect('/admin/claim/'.$id)->with('errorStatus', 'Claim Chưa có thông tin chuyển khoản vui lòng , Vui lòng (Ament -> Update) lại HBS , và thử lại sau');   
                }
                break;
            case 'CL_PAYMENT_METHOD_CH':
                if($HBS_CL_LINE->bank_name == null || $HBS_CL_LINE->bank_branch == null || $HBS_CL_LINE->bank_city == null){
                    return redirect('/admin/claim/'.$id)->with('errorStatus', 'Claim Chưa có thông tin Ngân Hàng nhận , Vui lòng (Ament -> Update) lại HBS , và thử lại sau');   
                }
                if($HBS_CL_LINE->beneficiary_name == null || $HBS_CL_LINE->id_passport_date_of_issue == null || $HBS_CL_LINE->id_passport_no == null){
                    return redirect('/admin/claim/'.$id)->with('errorStatus', 'Claim Chưa có thông tin ID Card Của Người Nhận , Vui lòng (Ament -> Update) lại HBS , và thử lại sau');   
                }
                break;
            case 'CL_PAYMENT_METHOD_CQ':
                if($HBS_CL_LINE->beneficiary_name == null){
                    return redirect('/admin/claim/'.$id)->with('errorStatus', 'Claim Chưa có thông tin Người Nhận , Vui lòng (Ament -> Update) lại HBS , và thử lại sau');   
                }
                break;
            default:
                break;
        }
        $rp = AjaxCommonController::sendPayment($request,$id);
        switch (data_get($rp,'code')) {
            case '00':
                // HBS_CL_CLAIM::where('CLAM_OID',$claim->code_claim)->update(['IS_FREEZED'=>1]);
                return redirect('/admin/claim/'.$id)->with('status', data_get($rp,'description'));
                break;
            case '01':
            case '02':
            case '03':
            case '04':
            case '05':
            case '06':
            case '07':
            case '08':
                return redirect('/admin/claim/'.$id)->with('errorStatus', data_get($rp,'description'));
            default:
                return redirect('/admin/claim/'.$id)->with('errorStatus', 'System error !!! please try again');
                break;
        }
    }

    public function setDebt(Request $request, $id){
        $rp = AjaxCommonController::setDebt($request->debt_id);
        switch (data_get($rp,'code')) {
            case '00':
                return redirect('/admin/claim/'.$id)->with('status', data_get($rp,'description'));
                break;
            case '01':
            case '02':
                return redirect('/admin/claim/'.$id)->with('errorStatus', data_get($rp,'description'));
            default:
                return redirect('/admin/claim/'.$id)->with('errorStatus', 'System error !!! please try again');
                break;
        }
    }

    public function payDebt(Request $request, $id){
        $pattern = '/[^0-9]+/';
        $rp = AjaxCommonController::payDebt($request , preg_replace($pattern, "", $request->paid_amt));
        switch (data_get($rp,'code')) {
            case '00':
                return redirect('/admin/claim/'.$id)->with('status', data_get($rp,'description'));
                break;
            case '01':
            case '02':
            case '03':
                return redirect('/admin/claim/'.$id)->with('errorStatus', data_get($rp,'description'));
            default:
                return redirect('/admin/claim/'.$id)->with('errorStatus', 'System error !!! please try again');
                break;
        }
    }
    
    public function attachEmail(Request $request, $id){
        $file = $request->file;
        $fileName = "attach_{$id}" . '.msg';
        $file->storeAs(config('constants.attachUpload'), $fileName);
    }

    public function setProvGOPPresAmt(InputGOPRequest $request, $id){
        $data = $claim = Claim::findOrFail($id);
        $userId = Auth::User()->id;
        $hospital_request = $claim->hospital_request;
        $claim->inbox_email()->updateOrCreate([],['from' => $request->from, 'to' =>  explode(",",$request->to), 'subject' => "$request->subject", 'body' => utf8_encode($request->body) ]);
        $url_form_request = null;
        $dataUpdate = [];
        if(file_exists( storage_path()."/app/public/attachEmail/"."/attach_{$id}" . '.msg')){
            $dataUpdate['url_attach_email'] =  "attach_{$id}" . '.msg';
        }
        

        if($hospital_request && $request->_url_form_request){
            $url_form_request = saveFile($request->_url_form_request, config('constants.sortedClaimUpload'),$hospital_request->url_form_request);
            $dataUpdate['url_form_request'] =  $url_form_request;
        }elseif($request->_url_form_request){
            $url_form_request = saveFile($request->_url_form_request, config('constants.sortedClaimUpload'));
            $dataUpdate['url_form_request'] =  $url_form_request;
        }

        if($request->_url_form_request){
            $patch_file_upload = storage_path("app/public/sortedClaim")."/". $url_form_request;
            $patch_file_convert = storage_path("app/public/sortedClaim")."/". 'cv_'. explode(".",$url_form_request)[0] .".pdf";
            
            $cm_run ="convert  ". $patch_file_upload." -quality 100  " .$patch_file_convert;
            $dataUpdate['url_form_request'] =   'cv_'. explode(".",$url_form_request)[0] .".pdf";
            exec($cm_run, $output);
            unlink($patch_file_upload);
        }
        $dataUpdate +=  [
            'prov_gop_pres_amt' => removeFormatPrice($request->prov_gop_pres_amt),
            'created_user' => $userId,
            'updated_user' => $userId,
            'type_gop' => 	$request->type_gop,
            'note' => 	$request->note,
            'incur_time' => $request->incur_time,
            'incur_to' => $request->incur_to,
            'incur_from' => $request->incur_from,
            'diagnosis' => $request->diagnosis,
            'incur_time_extb' => $request->incur_time_extb,
        ];
        try {
            
            DB::beginTransaction();
                $claim->hospital_request()->updateOrCreate(['claim_id' => $id]
                ,$dataUpdate);
                if ($request->_content != null) {
                    $dataItemNew = [];
                    foreach ($request->_idItem as $key => $value) {
                        if ($value == null) {
                            $dataItemNew[] = [
                                'claim_id' => $id,
                                'content' => $request->_content[$key],
                                'amount' => $request->_amount[$key],
                                'created_user' => $userId,
                                'updated_user' => $userId,
                            ];
                        } else {
                            $keynew = $key - 1;
                            $data->item_of_claim[$keynew]->updated_user = $userId;
                            $data->item_of_claim[$keynew]->content = $request->_content[$key];
                            $data->item_of_claim[$keynew]->amount = $request->_amount[$key];
                        }
                    }
                     //delete
                    $dataDel = ItemOfClaim::whereNotIn('id', array_filter($request->_idItem))->where('claim_id', $id);
                    $dataDel->delete();
                    // update
                    $data->push();
                    // new season price
                    $data->item_of_claim()->createMany($dataItemNew);
                } else {
                    $dataDel = ItemOfClaim::where('claim_id', $id);
                    $dataDel->delete();
                } // update and create new tour_set
                DB::commit();
                $request->session()->flash('status', __('message.update_claim'));
            return redirect('/admin/claim/'.$id);
        } catch (Exception $e) {
            Log::error(generateLogMsg($e));
            DB::rollback();
            $request->session()->flash(
                'errorStatus', 
                __('message.update_fail')
            );
            return redirect('/admin/claim/'.$id);
        }
    }
    public function requestManagerGOP(Request $request, $id){
        $claim = Claim::findOrFail($id);
        $claim_type = $claim->claim_type;
        
        $user = Auth::user();
        if($request->type_submit == 'request'){
            if($claim->url_file_sorted == null){
                return redirect('/admin/claim/'.$id)->with('errorStatus', 'Vui lòng update File vào "Tệp đã được sắp sếp" ');
            }
            $sumItemReject = 0 ;
            $payment_history_cps = json_decode(AjaxCommonController::getPaymentHistoryCPS($claim->code_claim_show)->getContent(),true);
            $approve_amt = data_get($payment_history_cps,'approve_amt');
            $present_amt = data_get($payment_history_cps,'present_amt');
            foreach ($claim->item_of_claim as $key => $value) {
                $sumItemReject += removeFormatPrice($value->amount);
            }
            if($sumItemReject != ($claim->hospital_request->prov_gop_pres_amt - $approve_amt)){
                return redirect('/admin/claim/'.$id)->with('errorStatus', 'Vui lòng nhập đúng những items reject hoặc số tiền yêu cầu ban đầu');
            }
            $claim->manager_gop_accept_pay = [
                'status' => 'request',
                'message' => '',
                'created_by' => $user->id,
                'created_at' => Carbon::now()->toDateTimeString(),
            ];
            $claim->save();
            $to_user = Setting::findOrFail(1)->manager_gop_claim;

            if (!empty($to_user)) {
                foreach ($to_user as $key => $value) {
                    $request2 = new Request([
                        'user' => $value,
                        'content' => 'Claim '.$claim->code_claim_show.' yêu cầu xác nhận "Thanh Toán" bởi '.$user->name.' Vui lòng kiểm tra lại thông tin tại : 
                        <a href="'.route('claim.show', $id).'">'.route('claim.show', $id).'</a>'
                    ]);
                    $send_mes = new SendMessageController();
                    $send_mes->sendMessage($request2);
                }
            }
            return redirect('/admin/claim/'.$id)->with('status', 'Đã gửi yêu cầu thành công');
        }

        if($request->type_submit == 'accept'){
            $to_user = [data_get($claim->manager_gop_accept_pay,'created_by')];
            $claim->manager_gop_accept_pay = [
                'status' => 'accept',
                'message' => '',
                'created_by' => $user->id,
                'created_at' => Carbon::now()->toDateTimeString(),
            ];
            $claim->save();
            $letter_template_id = LetterTemplate::where('name','Letter Payment (GOP)')->first()->id;
            $HBS_CL_CLAIM = HBS_CL_CLAIM::IOPDiag()->findOrFail($claim->code_claim);

            $export_letter = $claim->export_letter()->create([
                'letter_template_id' => $letter_template_id,
                'created_user' => $claim->created_user,
                'updated_user' => $user->id,
                'apv_amt' =>  $HBS_CL_CLAIM->sumAppAmt,
                'status' => 0,
            ]);
            //$approve_user_sign = getUserSignThumb($user->id);
            $approve_user_sign = $user->name;
            $per_approve_sign_replace = $claim_type == "P" ? getUserSignThumb() : getUserSign();
            $data_htm = $this->letter($letter_template_id ,  $claim->id ,$export_letter->id)['content'];
            $user_create = User::findOrFail($claim->created_user);
            $data_htm_apv = str_replace(['[[$per_approve_sign]]','[[$per_approve_sign_replace]]','[[$per_creater_sign]]'], [$approve_user_sign,$per_approve_sign_replace,$user_create->name], $data_htm);
            
            //sent Etalk 
            $body = [
                'user_email' => $user->email,
                'issue_id' => $claim->barcode,
                'text_note' => " Dear Team, \n PCV gửi là thông tin thanh toán và chi tiết theo như file đính kèm. \n Thanks,",
    
            ];
            $mpdf = new \Mpdf\Mpdf(['tempDir' => base_path('resources/fonts/')]);
                $mpdf->WriteHTML('
                <div style="position: absolute; right: 5px; top: 0px;font-weight: bold; ">
                    <img src="'.asset("images/header.jpg").'" alt="head">
                </div>');
                $mpdf->SetHTMLFooter('
                <div style="text-align: right; font-weight: bold;">
                    <img src="'.asset("images/footer.png").'" alt="foot">
                </div>');
                $mpdf->WriteHTML($data_htm_apv);
            $body['files'] = [
                        [
                            'name' => "letter_payment.pdf",
                            "content" => base64_encode($mpdf->Output('filename.pdf',\Mpdf\Output\Destination::STRING_RETURN))
                        ]
                    ];
            $diff = $HBS_CL_CLAIM->SumPresAmt - $HBS_CL_CLAIM->SumAppAmt ;
            
            if($HBS_CL_CLAIM->SumAppAmt == 0 ){
                $body['status_id'] = config('constants.status_mantic_value.declined');
            }elseif($diff == 0){
                $body['status_id'] = config('constants.status_mantic_value.accepted');
            }else {
                $body['status_id'] = config('constants.status_mantic_value.partiallyaccepted');
            }

            try {
                $res = PostApiMantic('api/rest/plugins/apimanagement/issues/add_note_reply_letter/files', $body);
                $res = json_decode($res->getBody(),true);
            } catch (Exception $e) {
    
                $request->session()->flash(
                    'errorStatus', 
                    generateLogMsg($e)
                );
                return redirect('/admin/claim/'.$id)->withInput();
            }

            //updata db
            $export_letter->update(['wait' => [
                'user' => $claim->created_user,
                'created_at' =>  Carbon::now()->toDateTimeString(),
                'data' => $data_htm
            ],
                'status' => 23, // Manager GOP approved
                'approve' =>[
                    'user' => $user->id,
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'data' => $data_htm_apv,
                ]
            ]);

            // notifi admin claim
            // $claim->report_admin()->updateOrCreate(['claim_id' => $claim->id]
            //     ,['REQUEST_SEND' => 1]);
            $to_admin_claim = User::whereHas("roles", function($q){ $q->where("name", "AdminClaim"); })->get()->pluck('id')->toArray();
            if (!empty($to_admin_claim)) {
                foreach ($to_admin_claim as $key => $value) {
                    $request2 = new Request([
                        'user' => $value,
                        'content' => 'Claim '.$claim->code_claim_show.' Đã Hoàn tất Vui lòng kiểm tra lại thông tin tại : 
                        <a href="'.route('claim.show', $claim->id).'">'.route('claim.show', $claim->id).'</a>'
                    ]);
                    $send_mes = new SendMessageController();
                    $send_mes->sendMessage($request2);
                }
            }
            
            
            if (!empty($to_user)) {
                foreach ($to_user as $key => $value) {
                    $request2 = new Request([
                        'user' => $value,
                        'content' => 'Claim '.$claim->code_claim_show.' được chấp thuận "Thanh Toán" bởi '.$user->name.' Vui lòng kiểm tra lại thông tin tại : 
                        <a href="'.route('claim.show', $id).'">'.route('claim.show', $id).'</a>'
                    ]);
                    $send_mes = new SendMessageController();
                    $send_mes->sendMessage($request2);
                }
            }
            return redirect('/admin/claim/'.$id)->with('status', 'Đã gửi yêu cầu thành công');
        }

        if($request->type_submit == 'reject'){
            $to_user = [data_get($claim->manager_gop_accept_pay,'created_by')];
            $claim->manager_gop_accept_pay = [
                'status' => 'reject',
                'message' => $request->message,
                'created_by' => $user->id,
                'created_at' => Carbon::now()->toDateTimeString(),
            ];
            $claim->save();
            if (!empty($to_user)) {
                foreach ($to_user as $key => $value) {
                    $request2 = new Request([
                        'user' => $value,
                        'content' => 'Claim '.$claim->code_claim_show.' bị từ chối "Thanh Toán" bởi '.$user->name.' Vui lòng kiểm tra lại thông tin tại : 
                        <a href="'.route('claim.show', $id).'">'.route('claim.show', $id).'</a>'
                    ]);
                    $send_mes = new SendMessageController();
                    $send_mes->sendMessage($request2);
                }
            }
            return redirect('/admin/claim/'.$id)->with('status', 'Đã gửi yêu cầu thành công');
        }
    }

    public function sendMailProvider(Request $request){
        $claim_id = $request->claim_id;
        $id = $request->export_letter_id;
        $export_letter = ExportLetter::findOrFail($id);
        $user = Auth::User();
        $claim  = Claim::itemClaimReject()->findOrFail($claim_id);
        $claim_type = $claim->claim_type;
        $per_approve_sign_replace = $claim_type == "P" ? getUserSignThumb() : getUserSign();

        $HBS_CL_CLAIM = HBS_CL_CLAIM::IOPDiag()->findOrFail($claim->code_claim);
        $diag_code = $HBS_CL_CLAIM->HBS_CL_LINE->pluck('diag_oid')->unique()->toArray();
        $namefile = Str::slug("{$export_letter->letter_template->name}_{$HBS_CL_CLAIM->memberNameCap}", '-');
        $template = 'templateEmail.sendProviderTemplate';
        // gop
        $mpdf = null;
        $match_form_gop = preg_match('/(FORM GOP)/', $export_letter->letter_template->name , $matches);
        $subject = 'Thư bảo lãnh .';
        $diag_text = implode(",",$HBS_CL_CLAIM->HBS_CL_LINE->pluck('RT_DIAGNOSIS.diag_desc_vn')->unique()->toArray());
        
        $request2 = new Request([
            'diag_code' => $diag_code,
            'id_claim' => $claim->code_claim
        ]);

        
        if($match_form_gop){
            if(data_get($claim->hospital_request , 'url_form_request') == null){
                return redirect('/admin/claim/'.$claim_id)->with('errorStatus', 'Vui lòng upload Files Claim Form khu vực input');
            }
            $template = 'templateEmail.sendProviderTemplate_input';
            $subject = 'Thư bảo lãnh đầu vào KH: '.$HBS_CL_CLAIM->MemberNameCap;
            $mpdf = new \Mpdf\Mpdf(['tempDir' => base_path('resources/fonts/'), 'margin_top' => 35]);
            $fileName = storage_path("app/public/sortedClaim")."/". $claim->hospital_request->url_form_request;
            
            $pagesInFile = $mpdf->SetSourceFile($fileName);


            for ($i = 1; $i <= $pagesInFile; $i++) {
                $mpdf->AddPage();
                $tplId = $mpdf->ImportPage($i);
                $mpdf->UseTemplate($tplId, 1, 1, 200, 285);
                $mpdf->WriteHTML('<div style="position: absolute; bottom: 0;
                right:5"><barcode code="'.$claim->barcode.'" type="C93"  height="1.3" />
                <div style="text-align: center">'.$claim->barcode.'</div></div>');
                
            }
            $mpdf->AddPage();
            $mpdf->WriteHTML('
            <div style="position: absolute; right: 5px; top: 0px;font-weight: bold; ">
                <img src="'.asset("images/header.jpg").'" alt="head">
            </div>');
            $mpdf->SetHTMLFooter('
            <div style="text-align: right; font-weight: bold;">
                <img src="'.asset("images/footer.png").'" alt="foot">
            </div>');
            $mpdf->WriteHTML('<div style="position: absolute; bottom:3;
                right:1"><barcode code="'.$claim->barcode.'" type="C93"  height="1.3" />
                <div style="text-align: center">'.$claim->barcode.'</div></div>');
            $mpdf->WriteHTML(str_replace("[[$per_approve_sign_replace]]",$per_approve_sign_replace,data_get($export_letter->approve, 'data')));

        }else{
            $template = 'templateEmail.sendProviderTemplate_output';
            $subject = 'Thư bảo lãnh đầu ra KH: '.$HBS_CL_CLAIM->MemberNameCap;
            $mpdf = new \Mpdf\Mpdf(['tempDir' => base_path('resources/fonts/')]);
            $mpdf->WriteHTML('
            <div style="position: absolute; right: 5px; top: 0px;font-weight: bold; ">
                <img src="'.asset("images/header.jpg").'" alt="head">
            </div>');
            $mpdf->SetHTMLFooter('
            <div style="text-align: right; font-weight: bold;">
                <img src="'.asset("images/footer.png").'" alt="foot">
            </div>');
            $mpdf->WriteHTML(str_replace("[[$per_approve_sign_replace]]",$per_approve_sign_replace,data_get($export_letter->approve, 'data')));
        }
        
        $old_msg = "";
        // Read email
        if($claim->inbox_email){
            
            $old_msg = $claim->inbox_email->body;
            preg_match('/(RE:)/',  $claim->inbox_email->subject, $matches_re, PREG_OFFSET_CAPTURE);
            $subject = $matches_re ? $claim->inbox_email->subject : "RE: " . $claim->inbox_email->subject ."-".$HBS_CL_CLAIM->MemberNameCap;
            $old_msg  = str_replace("\r\n", "<br>", utf8_decode($old_msg));
        }
        $user = Auth::User();
        $data = [];
        $incurDateTo = Carbon::parse($HBS_CL_CLAIM->FirstLine->incur_date_to);
        $incurDateFrom = Carbon::parse($HBS_CL_CLAIM->FirstLine->incur_date_from);
        $diffIncur = $incurDateTo->diffInDays($incurDateFrom);
        $data['diag_text'] = $diag_text;
        $data['incurDateTo'] = $incurDateTo->format('d-m-Y');
        $data['incurDateFrom'] = $incurDateFrom->format('d-m-Y');
        $data['diffIncur'] = $diffIncur;
        $data['old_msg'] = $old_msg;
        $data['HBS_CL_CLAIM'] = $HBS_CL_CLAIM;
        $data['Diagnosis'] = data_get($claim->hospital_request,'diagnosis',null) ?  data_get($claim->hospital_request,'diagnosis') : $HBS_CL_CLAIM->FirstLine->RT_DIAGNOSIS->diag_desc_vn;
        $data['attachment']['base64'] =  base64_encode($mpdf->Output('filename.pdf',\Mpdf\Output\Destination::STRING_RETURN)) ;
        $data['attachment']['filename'] = $namefile . ".pdf";
        $data['attachment']['filetype'] = "application/pdf";
        $data['email_reply'] = $user->email;
        $email_to = explode(",", $request->email_to);
        $email_to = array_diff( $email_to, ['admin@pacificcross.com.vn'] );
        
        sendEmailProvider($user, $email_to, 'provider', $subject, $data,$template);
        return redirect('/admin/claim/'.$claim_id)->with('status', 'Đã gửi thư cho provider thành công');
    }

    public function sendMailCustomer(Request $request){
       
        $claim_id = $request->claim_id;
        $id = $request->export_letter_id;
        $export_letter = ExportLetter::findOrFail($id);
        $user = Auth::User();
        $claim  = Claim::itemClaimReject()->findOrFail($claim_id);
        $claim_type = $claim->claim_type;
        $per_approve_sign_replace = $claim_type == "P" ? getUserSignThumb() : getUserSign();

        $HBS_CL_CLAIM = HBS_CL_CLAIM::IOPDiag()->findOrFail($claim->code_claim);
        $diag_code = $HBS_CL_CLAIM->HBS_CL_LINE->pluck('diag_oid')->unique()->toArray();
        $namefile = Str::slug("{$export_letter->letter_template->name}_{$HBS_CL_CLAIM->memberNameCap}", '-');
        $template = 'templateEmail.sendCustomer';
        $subject = '[PCV] Thông Báo Đến Khách Hàng: '.$HBS_CL_CLAIM->MemberNameCap;
        $mpdf = new \Mpdf\Mpdf(['tempDir' => base_path('resources/fonts/'), 'margin_top' => 35]);
        $mpdf->WriteHTML('
        <div style="position: absolute; right: 5px; top: 0px;font-weight: bold; ">
            <img src="'.asset("images/header.jpg").'" alt="head">
        </div>');
        $mpdf->SetHTMLFooter('
        <div style="text-align: right; font-weight: bold;">
            <img src="'.asset("images/footer.png").'" alt="foot">
        </div>');
        $mpdf->WriteHTML('<div style="position: absolute; bottom: 3;
            right:1"><barcode code="'.$claim->barcode.'" type="C93"  height="1.3" />
            <div style="text-align: center">'.$claim->barcode.'</div></div>');
        if($export_letter->approve != null){
                    
            $content = $export_letter->approve['data'];
        }else{
            $content = $export_letter->wait['data'];
        }
        $mpdf->WriteHTML($content);
        $user = Auth::User();
        $data = [];
        $data['HBS_CL_CLAIM'] = $HBS_CL_CLAIM;
        $data['name_lette'] = $export_letter->letter_template->name;
        $data['attachment']['base64'] =  base64_encode($mpdf->Output('filename.pdf',\Mpdf\Output\Destination::STRING_RETURN)) ;
        $data['attachment']['filename'] = $namefile . ".pdf";
        $data['attachment']['filetype'] = "application/pdf";
        $data['email_reply'] = $user->email;
        $email_to = explode(",", $request->email_to);
        $email_to = array_diff( $email_to, ['admin@pacificcross.com.vn'] );
        
        sendEmailProvider($user, $email_to, 'provider', $subject, $data,$template,'msigclaims@pacificcross.com.vn');
        return redirect('/admin/claim/'.$claim_id)->with('status', 'Đã gửi thư cho Custommer thành công');
    }

    public function setJetcase(Request $request, $id){

        $claim = Claim::findOrFail($id);
        $claim->jetcase = $request->jetcase ? $request->jetcase : 0;
        $claim->save();
        return redirect('/admin/claim/'.$id)->with('status', 'Đã cập nhật thành công');
    }

    public function unfreezed(Request $request, $id){
        $claim = Claim::findOrFail($id);
        $user = Auth::User();
        HBS_CL_CLAIM::where('CLAM_OID',$claim->code_claim)->update(['IS_FREEZED'=>0]);
        $claim->log_unfreezed()->create([
            'cl_no' => $claim->code_claim_show,
            'reason' => $request->reason,
            'desc' => $request->desc,
            'created_user' => $user->id,
            'updated_user' => $user->id,
        ]);
        return redirect('/admin/claim/'.$id)->with('status', 'Đã mở khóa thành công');
    }

    public function closeClaim(Request $request, $id){
        $user = Auth::User();
        $claim = Claim::findOrFail($id);
        $HBS_CL_CLAIM = HBS_CL_CLAIM::IOPDiag()->findOrFail($claim->code_claim);
        $count_orther_close = $HBS_CL_CLAIM->HBS_CL_LINE->where('scma_oid_cl_line_status',"!=",'CL_LINE_STATUS_CL')->count();
        if($count_orther_close > 0){
            return redirect('/admin/claim/'.$id)->with('errorStatus', 'Vui lòng kiểm tra HBS tất cả claim line phải chuyển sang trang thái Close ');
        }

        $body = [
            'user_email' => $user->email,
            'issue_id' => $claim->barcode,
            'text_note' => '.',
            'status_id' =>  config('constants.status_mantic_value.closed'),
        ];
        try {
            $res = PostApiMantic('api/rest/plugins/apimanagement/issues/add_note_reply_letter/files', $body);
            $res = json_decode($res->getBody(),true);
        } catch (Exception $e) {

            $request->session()->flash(
                'errorStatus', 
                generateLogMsg($e)
            );
            return redirect('/admin/claim/'.$id)->withInput();
        }

        
        // HBS_CL_CLAIM::where('CLAM_OID',$claim->code_claim)->update(['IS_FREEZED'=>1]);
        // $claim->log_unfreezed()->create([
        //     'cl_no' => $claim->code_claim_show,
        //     'reason' => 'Close',
        //     'desc' => $request->desc,
        //     'created_user' => $user->id,
        //     'updated_user' => $user->id,
        // ]);
        return redirect('/admin/claim/'.$id)->with('status', 'Đã Close Claim thành công');
    }

    public function setAdminFee(Request $request, $id){

        $claim = Claim::findOrFail($id);
        $claim->include_admin_fee = $request->include_admin_fee ? $request->include_admin_fee : 0;
        $claim->save();
        return redirect('/admin/claim/'.$id)->with('status', 'Đã cập nhật thành công');
    }

    public function claimHistory($HBS_CL_CLAIM , $lang = null ){
        $pocy_no = $HBS_CL_CLAIM->Police->pocy_no;
        $plan_oid = data_get($HBS_CL_CLAIM->PolicePlan,'PD_PLAN.plan_oid');
        
        $limit_benefit = [];
        $limit = \App\HBS_VW_RP_POLICY_PLAN_BEN_TYPE::where('plan_oid',$plan_oid)->with(['PD_PLAN'=>function ($q) {
            $q->with(['PD_PLAN_LIMIT' => function ($k) {
                $k->where('limit_type',"T");
                $k->with('PD_BEN_HEAD');
            }]);
        }])->get();
        
        foreach ($limit as $key => $value) {
           $str_type = $value->benefit_type;
           $type = $value->scma_oid_ben_type;
           foreach($value->PD_PLAN->PD_PLAN_LIMIT as $key2 => $value2){
               if($value2->PD_BEN_HEAD[0]->scma_oid_ben_type == $type){
                    $limit_benefit[] = $str_type . ": " . formatPrice($value2->amt_yr);
               }
           }
        }
        $limit_benefit = collect($limit_benefit)->unique()->toArray();
        $claims = $HBS_CL_CLAIM->historyClaimCurrentYearCurrent;
        $sum_app = 0;
        $sum_row = $claims->count(); 
        $claim_no = $lang == 'en' ? 'Claim No.' : 'Claim số';
        $limit_payment = $lang == 'en' ? 'Limit' : 'Giới hạn thanh toán';
        $incur_date = $lang == 'en' ? 'Treatment Period' : 'Ngày khám';
        $diagnosis = $lang == 'en' ? 'Diagnosis' : 'Bệnh';
        $benefit = $lang == 'en' ? 'Benefit' : 'Quyền lợi <br />(IP/OP/DT/PA..)';
        $app_amt = $lang == 'en' ? 'Paid amount <br />(VND)' : 'Số tiền bồi thường <br />(VNĐ)';
        $html = '
        <style type="text/css">
            table { page-break-inside:auto ; font-size: 11pt; font-family: arial, helvetica, sans-serif;}
            tr    { page-break-inside:avoid; page-break-after:auto ; font-size: 11pt; font-family: arial, helvetica, sans-serif;}
            thead { display:table-header-group ; font-size: 11pt; font-family: arial, helvetica, sans-serif;}
            tfoot { display:table-footer-group ; font-size: 11pt; font-family: arial, helvetica, sans-serif;}
        </style>
                <table style=" border: 1px solid #1e91e3; border-collapse: collapse;width: 100%">
                    <thead style="background: aliceblue">
                        <tr>
                            <th style="border: 1px solid #1e91e3 ; font-family: arial, helvetica, sans-serif ; font-size: 11pt">'.$claim_no.'</th>
                            <th style="border: 1px solid #1e91e3 ; font-family: arial, helvetica, sans-serif ; font-size: 11pt">'.$limit_payment.'</th>
                            <th style="border: 1px solid #1e91e3 ; font-family: arial, helvetica, sans-serif ; font-size: 11pt">'.$incur_date.'</th>
                            <th style="border: 1px solid #1e91e3 ; font-family: arial, helvetica, sans-serif ; font-size: 11pt">'.$diagnosis.'</th>
                            <th style="border: 1px solid #1e91e3 ; font-family: arial, helvetica, sans-serif ; font-size: 11pt">'.$benefit.'</th>
                            <th style="border: 1px solid #1e91e3 ; font-family: arial, helvetica, sans-serif ; font-size: 11pt">'.$app_amt.'</th>
                        </tr>
                    </thead>
                    <tbody>';
        foreach ($claims as $key => $value) {
            $incur =$value->HBS_CL_LINE->map(function ($c) {
                $q=  collect($c)->only(['incur_date_from', 'incur_date_to']);
                if($q['incur_date_from'] == $q['incur_date_to']){
                    return str_replace(" 00:00:00", "",$q['incur_date_from']) ;
                }else{
                    return str_replace(" 00:00:00", "",$q['incur_date_from']) .' to ' . str_replace(" 00:00:00", "",$q['incur_date_to']);
                }
            })->unique()->toArray();
            
            $hbs_diag = $value->HBS_CL_LINE->map(function ($c) {
                return empty($c->RT_DIAGNOSIS->diag_desc_vn) ? $c->RT_DIAGNOSIS->diag_desc : $c->RT_DIAGNOSIS->diag_desc_vn;
            })->unique()->toArray();
            $benhead = $value->HBS_CL_LINE->map(function ($c) {
                return str_replace("BENEFIT_TYPE_", "", $c->PD_BEN_HEAD->scma_oid_ben_type);
            })->unique()->toArray();
           
            $sum_app += $value->SumAppAmt;
            $html .=    '<tr>
                            <td style="border: 1px solid #1e91e3 ; font-family: arial, helvetica, sans-serif ; font-size: 11pt; color: #1e91e3;">'.$value->cl_no.'</td>';
            if($key == 0){
                $html .= '<td rowspan="'.$sum_row.'" style="border: 1px solid #1e91e3 ; font-family: arial, helvetica, sans-serif ; font-size: 11pt; color: #1e91e3;">'.implode("<br />",$limit_benefit).'</td>';
            }
                            
            $html .=        '<td style="border: 1px solid #1e91e3 ; font-family: arial, helvetica, sans-serif ; font-size: 11pt; color: #1e91e3;">'.implode(" ; ",$incur).'</td>
                            <td style="border: 1px solid #1e91e3 ; font-family: arial, helvetica, sans-serif ; font-size: 11pt; color: #1e91e3;">'.implode(" ; ",$hbs_diag).'</td>
                            <td style="border: 1px solid #1e91e3 ; font-family: arial, helvetica, sans-serif ; font-size: 11pt; color: #1e91e3;">'.implode(" ; ",$benhead).'</td>
                            <td style="border: 1px solid #1e91e3 ; font-family: arial, helvetica, sans-serif ; font-size: 11pt; color: #1e91e3;">'.formatPrice($value->SumAppAmt).'</td>
                        </tr>';
        }
        $html .=    '<tr>
                        <th style="border: 1px solid #1e91e3 ;font-family: arial, helvetica, sans-serif ; font-size: 11pt" >Tổng cộng:</th>
                        <th colspan="4" style="border: 1px solid #1e91e3 ; font-family: arial, helvetica, sans-serif ; font-size: 11pt; color: #1e91e3;">'.'</th>
                        <th style="border: 1px solid #1e91e3 ; font-family: arial, helvetica, sans-serif ; font-size: 11pt; color: #1e91e3;">'.formatPrice($sum_app).'</th>
                    </tr>';
        $html .= '</tbody>';
        $html .= '</table>';
        return $html;
    }

    

    public function update_invoice(Request $request,$id){
        $claim = Claim::findOrFail($id);
        $request->validate([
            'vat_type' => 'required',
        ]);
        $claim->original_invoice_type = $request->original_invoice_type == "Yes" ? "Yes" : "No";
        $claim->e_invoice_type = $request->e_invoice_type == "Yes"? "Yes" : "No";
        $claim->converted_invoice_type = $request->converted_invoice_type == "Yes"? "Yes" : "No";
        
        $claim->original_invoice_no = $request->original_invoice_no;
        $claim->e_invoice_no = $request->e_invoice_no;
        $claim->converted_invoice_no = $request->converted_invoice_no;
        $claim->original_invoice_no_not_ready = $request->original_invoice_no_not_ready;
        $claim->e_invoice_no_not_ready = $request->e_invoice_no_not_ready;
        $claim->converted_invoice_no_not_ready = $request->converted_invoice_no_not_ready;
        //remove type not check
        $not_check = array_diff(array_keys(config('constants.invoice_type')),$request->vat_type);
        foreach ($not_check as $key => $value) {
            $field =  $value.'_no';
            $claim->$field = null;
        }
        $claim->save();
        
        return redirect('/admin/claim/'.$id)->with('status', 'Đã Update Claim thành công');
        
    }

    public function sendNoticationMobile(Request $request, $id){
        $claim = Claim::findOrFail($id);
        send_message_mobile('Pacific Cross Viet Nam', $request->message, $claim);

        return redirect('/admin/claim/'.$id)->with('status', 'Đã Notication thành công');
    }

    // change Etalk 
    public function changeStatusEtalk(Request $request){
        $claim_id = $request->claim_id;
        $user = Auth::User();
        $claim  = Claim::itemClaimReject()->findOrFail($claim_id);
        $barcode = $claim->barcode;
        $HBS_CL_CLAIM = HBS_CL_CLAIM::IOPDiag()->findOrFail($claim->code_claim);
        $body = [
            'user_email' => $user->email,
            'issue_id' => $barcode,
            'text_note' => 'Cập nhật lại status',

        ];
        $diff = $HBS_CL_CLAIM->SumPresAmt - $HBS_CL_CLAIM->SumAppAmt ;
        if($HBS_CL_CLAIM->SumAppAmt == 0 ){
                $body['status_id'] = config('constants.status_mantic_value.declined');
        }elseif($diff == 0){
                $body['status_id'] = config('constants.status_mantic_value.accepted');
        }else {
                $body['status_id'] = config('constants.status_mantic_value.partiallyaccepted');
        }
        
        try {
            $res = PostApiMantic('api/rest/plugins/apimanagement/issues/add_note_reply_letter/files', $body);
            $res = json_decode($res->getBody(),true);
        } catch (Exception $e) {

            $request->session()->flash(
                'errorStatus', 
                generateLogMsg($e)
            );
            return redirect('/admin/claim/'.$claim_id)->withInput();
        }
        return redirect('/admin/claim/'.$claim_id)->with('status', __('message.update_claim'));
    }

    // custommerConfirm 
    public function custommerConfirm(Request $request,$id){
        $request->validate([
            '_url_form_request' => 'required',
            'note' => 'required',
        ],
        [
            '_url_form_request.required' => 'Bạn cần phải gửi file đính kèm của khách hàng xác nhận',

        ]
    );
        $user = Auth::User();
        $claim  = Claim::findOrFail($id);
        $body = [];
        $body = [
            'user_email' => $user->email,
            'issue_id' => $claim->barcode,
            'text_note' =>  $request->note,
        ];
        
        $body['files'] = [
                [
                    'name' => $request->file('_url_form_request')->getClientOriginalName(),
                    "content" => base64_encode($request->file('_url_form_request')->get())
                ]
            ];
        
        
        try {
            
            $res = PostApiMantic('api/rest/plugins/apimanagement/issues/add_note_reply_letter/files', $body);

            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => config('constants.token_mantic'),
            ];
            $client = new \GuzzleHttp\Client([
                    'headers' => $headers
                ]);
            $response = $client->request("PATCH", config('constants.url_mantic_api') . "api/rest/issues/$claim->barcode", ['form_params' => [
                "custom_fields" => [
                    [
                        "field" => [
                            'name' => 'Client Approved'
                        ],
                        "value" => "Yes"
                    ]
                ]
            ]]);
            
            
        } catch (Exception $e) {

            $request->session()->flash(
                'errorStatus', 
                generateLogMsg($e)
            );
            return redirect('/admin/claim/'.$claim_id)->withInput();
        }
        
        return redirect('/admin/claim/'.$id)->with('status', __('message.update_claim'));
    }
}
