<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateClaimWordSheetRequest;
use App\Http\Requests\UpdateClaimWordSheetRequest;
use App\ClaimWordSheet;
use App\HBS_MR_MEMBER;
use Auth;
use App\ItemOfClaim;
use App\User;
use App\Claim;
use App\HBS_CL_CLAIM;
use App\MANTIS_BUG;
use App\ReasonReject;
use Illuminate\Http\Request;
use Flash;
use Response;
use Illuminate\Support\Arr;
use PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DB;
use Exception;
use Log;

class ClaimWordSheetController extends Controller
{
    /** @var  ClaimWordSheetRepository */
    private $claimWordSheetRepository;

    public function __construct()
    {
        //$this->authorizeResource(ClaimWordSheet::class);
        parent::__construct();
    }

    /**
     * Display a listing of the ClaimWordSheet.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $search_params = [
            'created_user' => $request->get('created_user'),
            'created_at' => $request->get('created_at'),
            'updated_user' => $request->get('updated_user'),
            'updated_at' => $request->get('updated_at'),
            'status' => $request->get('status'),
        ];
        $admin_list = User::getListIncharge();
        $limit_list = config('constants.limit_list');
        $limit = $request->get('limit');
        $per_page = !empty($limit) ? $limit : Arr::first($limit_list);

        $claimWordSheets =  ClaimWordSheet::findByParams($search_params)->whereIn('status', [1,2])->orderBy('id', 'desc');
        $claimWordSheets  = $claimWordSheets->paginate($per_page);

        return view('claim_word_sheets.index', compact('search_params', 'admin_list', 'limit', 'limit_list', 'claimWordSheets' ));           
    }

    /**
     * Show the form for creating a new ClaimWordSheet.
     *
     * @return Response
     */
    /**
     * Store a newly created ClaimWordSheet in storage.
     *
     * @param CreateClaimWordSheetRequest $request
     *
     * @return Response
     */
    public function store(CreateClaimWordSheetRequest $request)
    {
        $userId = Auth::User()->id;
        $data = $request->except([]);
        $data['created_user'] = $userId;
        $data['updated_user'] = $userId;
        ClaimWordSheet::create($data);
        $request->session()->flash('status', 'Claim Word Sheet saved successfully.');

        return redirect(route('claim.show',['claim'=>$request->claim_id]));
    }

    /**
     * Display the specified ClaimWordSheet.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show(ClaimWordSheet $claimWordSheet)
    {
        
        $claim  = Claim::itemClaimReject()->findOrFail($claimWordSheet->claim_id);
        $HBS_CL_CLAIM = HBS_CL_CLAIM::IOPDiag()->findOrFail($claim->code_claim);
        $member = HBS_MR_MEMBER::where('MBR_NO',$claimWordSheet->mem_ref_no)->first();
        $condition_field = function($q) use ($claimWordSheet){
            $q->where('field_id', '4');
            $q->where('value',$claimWordSheet->mem_ref_no);
        };
        $condition_field_show = function($q) use ($claimWordSheet){
            $q->whereIn('field_id',[2,14,15]);
        };
        //$MANTIS_BUG = MANTIS_BUG::where("project_id",1)->with(['CUSTOM_FIELD_STRING' => $condition_field_show, "BUG_TEXT"])->whereHas('CUSTOM_FIELD_STRING',$condition_field)->limit(7)->orderBy('id', 'DESC')->get();
        $claim_line = $member->ClaimLine;
        //rmove claim line curent
        $arr_clli_oid = $HBS_CL_CLAIM->HBS_CL_LINE->pluck('clli_oid')->toArray();
        $claim_line = $claim_line->whereNotIn('clli_oid',$arr_clli_oid);
        $log_history = $claimWordSheet->log;
        $listReasonReject = ReasonReject::orderBy('id', 'desc')->pluck('name', 'id');
        $data_type_of_visit_hbs = IOPDiagWookSheet($HBS_CL_CLAIM);
        if($claimWordSheet->type_of_visit == [] || $claimWordSheet->type_of_visit == null){
            $claimWordSheet->type_of_visit = $data_type_of_visit_hbs;
        }
        $bnf = $claim->item_of_claim->pluck('benefit')->unique();
        $bnf = $bnf->map(function ($item, $key) {
                return preg_replace("/[^0-9]+/", "", $item);;
            //in your case you will check and set your image source here
        });
        $count_bnf = $bnf->max() == null ? 0 : $bnf->max();
        //dd($member->MR_MEMBER_EVENT->where('scma_oid_event_code', 'EVENT_CODE_EXPL')->first());
        $benefit = \App\HbsBenhead::pluck('name','name');
        
        return view('claim_word_sheets.show', compact('claimWordSheet', 'claim', 'HBS_CL_CLAIM', 'member','claim_line', 'log_history', 'listReasonReject','count_bnf', 'benefit'));
    }

    public function pdf(ClaimWordSheet $claimWordSheet){

        $claim  = Claim::itemClaimReject()->findOrFail($claimWordSheet->claim_id);
        $HBS_CL_CLAIM = HBS_CL_CLAIM::IOPDiag()->findOrFail($claim->code_claim);
        $member = HBS_MR_MEMBER::where('MBR_NO',$claimWordSheet->mem_ref_no)->first();
        $claim_line = $member->ClaimLine;
        //rmove claim line curent
        $arr_clli_oid = $HBS_CL_CLAIM->HBS_CL_LINE->pluck('clli_oid')->toArray();
        $claim_line = $claim_line->whereNotIn('clli_oid',$arr_clli_oid);

        $log_history = $claimWordSheet->log;
        $mpdf = new \Mpdf\Mpdf(['tempDir' => base_path('resources/fonts/')]);
        $mpdf->WriteHTML(view('claim_word_sheets.pdf', compact('claimWordSheet', 'claim', 'HBS_CL_CLAIM', 'member','claim_line', 'log_history'))->render());
        $mpdf->SetHTMLFooter('
        <div style="text-align: right; font-weight: bold;">
            <img src="'.asset("images/footer.png").'" alt="foot">
        </div>');
        header("Content-Type: application/pdf");
        header("Expires: 0");//no-cache
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");//no-cache
        header("content-disposition: attachment;filename=worksheet.pdf");
        echo $mpdf->Output('filename.pdf',\Mpdf\Output\Destination::STRING_RETURN);
    }

    public function summary(ClaimWordSheet $claimWordSheet){
        $path_file = [] ;
        $claim  = Claim::itemClaimReject()->findOrFail($claimWordSheet->claim_id);
        $HBS_CL_CLAIM = HBS_CL_CLAIM::IOPDiag()->findOrFail($claim->code_claim);
        $member = HBS_MR_MEMBER::where('MEMB_REF_NO',$claimWordSheet->mem_ref_no)->first();
        $claim_line = $member->ClaimLine;
        //rmove claim line curent
        $arr_clli_oid = $HBS_CL_CLAIM->HBS_CL_LINE->pluck('clli_oid')->toArray();
        $claim_line = $claim_line->whereNotIn('clli_oid',$arr_clli_oid);
        
        $mpdf = new \Mpdf\Mpdf(['tempDir' => base_path('resources/fonts/')]);
        $mpdf->WriteHTML(view('claim_word_sheets.pdf', compact('claimWordSheet', 'claim', 'HBS_CL_CLAIM', 'member','claim_line'))->render());
        $mpdf->SetHTMLFooter('
        <div style="text-align: right; font-weight: bold;">
            <img src="'.asset("images/footer.png").'" alt="foot">
        </div>');
        $pdf = $mpdf->Output('filename.pdf',\Mpdf\Output\Destination::STRING_RETURN);
        $count_page = count($mpdf->pages);
        $file_name =  md5(Str::random(12).time());
        Storage::put('public/cache/' . $file_name, $pdf);
        $path_file[] = storage_path("app/public/cache/$file_name") ;

        

        $filename = config('constants.url_mantic') ."plugin.php?page=DownloadFiles/file_summary_download.php&bug_id=" . $claim->barcode;
        $handle = fopen($filename, "r");
        $file_contents = stream_get_contents($handle);
        fclose($handle);
        if($file_contents != ""){
            $file_name_mantis =  md5(Str::random(11).time());
            Storage::put('public/cache/' . $file_name_mantis, $file_contents);
            $path_file[] = storage_path("app/public/cache/$file_name_mantis") ;
            if($claimWordSheet->old_number_page_send != 0){
                
                $file_name_mantis_output =  md5(Str::random(9).time());
                $FirstPage = $claimWordSheet->old_number_page_send + 1 ;
                $cm_run ="gs -sDEVICE=pdfwrite -dNOPAUSE -dQUIET -dBATCH -dFirstPage={$FirstPage} -sOutputFile=". storage_path("app/public/cache/$file_name_mantis_output") ." ".storage_path("app/public/cache/$file_name_mantis");
                exec($cm_run);
                Storage::delete(str_replace(storage_path("app")."/", "", $path_file[1]));
                $path_file[1] = storage_path("app/public/cache/$file_name_mantis_output");
            }
        }

        $file_name_combine =  md5(Str::random(10).time()) .".pdf";
        $path_file[] = storage_path("app/public/cache/$file_name_combine");
        $cm_run = "gs -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -dPDFSETTINGS=/prepress -sOutputFile=".storage_path("app/public/cache/$file_name_combine"). 
        " -dBATCH " . implode(" ",$path_file);
        exec($cm_run);
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
                        'contents' => fopen(storage_path("app/public/cache/$file_name_combine"),'r')
                    ]
                ]
            ];
            
            $res = PostApiManticHasFile('api/rest/plugins/apimanagement/issues/upload_sumary/files', $body);
            $statusCode =  $res->getStatusCode();
            $mes = $res->getReasonPhrase();
        } catch (Exception $e) {
            
        }

        foreach ($path_file as $key => $value){
            $path_file[$key]  = str_replace(storage_path("app")."/", "", $value);
        }
        Storage::delete($path_file);
        $claimWordSheet->old_number_page_send = $count_page;
        $claimWordSheet->save();
        return response()->json(['status' => 'success' , 'message' => $mes]);
    }

    public function sendSortedFile(ClaimWordSheet $claimWordSheet){
        $path_file = [] ;
        $claim  = Claim::itemClaimReject()->findOrFail($claimWordSheet->claim_id);
        $HBS_CL_CLAIM = HBS_CL_CLAIM::IOPDiag()->findOrFail($claim->code_claim);
        $member = HBS_MR_MEMBER::where('MBR_NO',$claimWordSheet->mem_ref_no)->first();
        $claim_line = $member->ClaimLine;
        //rmove claim line curent
        $arr_clli_oid = $HBS_CL_CLAIM->HBS_CL_LINE->pluck('clli_oid')->toArray();
        $claim_line = $claim_line->whereNotIn('clli_oid',$arr_clli_oid);
        
        $mpdf = new \Mpdf\Mpdf(['tempDir' => base_path('resources/fonts/')]);
        $mpdf->WriteHTML(view('claim_word_sheets.pdf', compact('claimWordSheet', 'claim', 'HBS_CL_CLAIM', 'member','claim_line'))->render());
        $mpdf->SetHTMLFooter('
        <div style="text-align: right; font-weight: bold;">
            <img src="'.asset("images/footer.png").'" alt="foot">
        </div>');
        $pdf = $mpdf->Output('filename.pdf',\Mpdf\Output\Destination::STRING_RETURN);
        $count_page = count($mpdf->pages);
        $file_name =  md5(Str::random(12).time());
        Storage::put('public/cache/' . $file_name, $pdf);
        $path_file[] = storage_path("app/public/cache/$file_name") ;

        
        if($claim->url_file_sorted && file_exists(storage_path('app/public/sortedClaim/'. $claim->url_file_sorted))){
            $filename_sorted = storage_path('app/public/sortedClaim/'. $claim->url_file_sorted);
            $handle = fopen($filename_sorted, "r");
            $file_contents = stream_get_contents($handle);
            fclose($handle);
            if($file_contents != ""){
                $file_name_mantis =  md5(Str::random(11).time());
                Storage::put('public/cache/' . $file_name_mantis, $file_contents);
                $path_file[] = storage_path("app/public/cache/$file_name_mantis") ;
                if($claimWordSheet->old_number_page_send != 0){
                    
                    $file_name_mantis_output =  md5(Str::random(9).time());
                    $FirstPage = $claimWordSheet->old_number_page_send + 1 ;
                    $cm_run ="gs -sDEVICE=pdfwrite -dNOPAUSE -dQUIET -dBATCH -dFirstPage={$FirstPage} -sOutputFile=". storage_path("app/public/cache/$file_name_mantis_output") ." ".storage_path("app/public/cache/$file_name_mantis");
                    exec($cm_run);
                    Storage::delete(str_replace(storage_path("app")."/", "", $path_file[1]));
                    $path_file[1] = storage_path("app/public/cache/$file_name_mantis_output");
                }
            }
        }else{
            Storage::put('public/sortedClaim/' . $file_name .'.pdf', $pdf);
            $claim->url_file_sorted = $file_name .'.pdf';
            $claim->push();
        }
        

        $cm_run = "gs -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -dPDFSETTINGS=/prepress -sOutputFile=".storage_path("app/public/sortedClaim/{$claim->url_file_sorted}"). 
        " -dBATCH " . implode(" ",$path_file);
        exec($cm_run);
        

        foreach ($path_file as $key => $value){
            $path_file[$key]  = str_replace(storage_path("app")."/", "", $value);
        }
        Storage::delete($path_file);
        $claimWordSheet->old_number_page_send = $count_page;
        $claimWordSheet->save();
        return response()->json(['status' => 'success' , 'message' => 'Ok']);
    }
    /**
     * Show the form for editing the specified ClaimWordSheet.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit(ClaimWordSheet $claimWordSheet)
    {
        
        return view('claim_word_sheets.edit',  compact('claimWordSheet'));
    }

    /**
     * Update the specified ClaimWordSheet in storage.
     *
     * @param int $id
     * @param UpdateClaimWordSheetRequest $request
     *
     * @return Response
     */
    public function update(ClaimWordSheet $claimWordSheet, UpdateClaimWordSheetRequest $request)
    {
        $data = $request->except(['table2_parameters']);
        if (isset($data["benefit"])){
            $data["benefit"] = array_values($data["benefit"]);
        }else{
            $data["benefit"] = null;
        }
        $userId = Auth::User()->id;
        $data['updated_user'] = $userId;
        
        try {
            DB::beginTransaction();
            $claim_word_sheet = ClaimWordSheet::updateOrCreate(['id' => $claimWordSheet->id], $data);
            // add reject
            $data = Claim::findOrFail($claimWordSheet->claim_id);
            if ($request->_content != null) {
                $dataItemNew = [];
                foreach ($request->_idItem as $key => $value) {
                    if ($value == null) {
                        $dataItemNew[] = [
                            'claim_id' => $data->id,
                            'reason_reject_id' => $request->_reasonInject[$key],
                            'content' => $request->_content[$key],
                            'amount' => $request->_amount[$key],
                            'benefit' => $request->_benefit[$key],
                            'parameters' => data_get($request->table2_parameters, $key),
                            'created_user' => $userId,
                            'updated_user' => $userId,
                        ];
                    } else {
                        $keynew = $key - 1;
                        $data->item_of_claim[$keynew]->updated_user = $userId;
                        $data->item_of_claim[$keynew]->reason_reject_id = $request->_reasonInject[$key];
                        $data->item_of_claim[$keynew]->content = $request->_content[$key];
                        $data->item_of_claim[$keynew]->benefit = $request->benefit[$key];
                        $data->item_of_claim[$keynew]->parameters = data_get($request->table2_parameters, $key);
                        $data->item_of_claim[$keynew]->amount = $request->_amount[$key];
                    }
                }
                //delete
                $dataDel = ItemOfClaim::whereNotIn('id', array_filter($request->_idItem))->where('claim_id', $data->id);
                $dataDel->delete();
                // update
                $data->save();
                // new season price
                $data->item_of_claim()->createMany($dataItemNew);

            } else {
                $dataDel = ItemOfClaim::where('claim_id', $data->id);
                $dataDel->delete();
            } // update and create new tour_set
            //end
            DB::commit();
        } catch (Exception $e) {
            Log::error(generateLogMsg($e));
            DB::rollback();
            $request->session()->flash('errorStatus', __('message.update_fail'));
            return redirect(route('claimWordSheets.show', $claimWordSheet->id));
        }
        
        if($request->status == 1){
            $arrUserId = User::whereHas("roles", function($q){ $q->where("name", "Medical"); })->pluck('id')->toArray();
            $content = Auth::User()->name . " yêu cầu hỗ trợ tư vấn Claim work sheet : <a href = '" . 
            route('claimWordSheets.show', $claimWordSheet->id) ."'>". 
            route('claimWordSheets.show',  $claimWordSheet->id)."</a>";
            notifi_system($content, $arrUserId);
        }
        if($request->status == 2){
            
            $content = Auth::User()->name . " đã trả lời yêu cầu hỗ trợ tư vấn Claim work sheet : <a href = '" . 
            route('claimWordSheets.show', $claimWordSheet->id) ."'>". 
            route('claimWordSheets.show',  $claimWordSheet->id)."</a>";
            notifi_system($content, [$claimWordSheet->created_user]);
        }
        $request->session()->flash('status', 'Claim Word Sheet updated successfully.'); 
        return redirect(route('claimWordSheets.show', $claimWordSheet->id));
    }

    /**
     * Remove the specified ClaimWordSheet from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(ClaimWordSheet $claimWordSheet)
    {
        $claimWordSheet->delete();
        return redirect(route('claimWordSheets.index'))->with('status', 'Claim Word Sheet deleted successfully.');
    }
}
