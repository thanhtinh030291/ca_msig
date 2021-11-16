<?php

namespace App\Http\Controllers;
use App\User;
use Auth;
use App\Setting;
use App\Claim;
use App\HBS_CL_CLAIM;
use App\Provider;
use App\HBS_PV_PROVIDER;
use App\LogHbsApproved;
use App\HBS_PD_BEN_HEAD;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\HbsBenhead;
use DB;
use Webklex\IMAP\Client;


class SettingController extends Controller
{
    
    //use Authorizable;
    public function __construct()
    {
        //$this->authorizeResource(Product::class);
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $setting = Setting::where('id', 1)->first();
        if($setting === null){
            $setting = Setting::create([]);
        }
        $admin_list = User::getListIncharge();
        return view('settingManagement.index', compact('setting','admin_list'));
    }




    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $request->except([]);
        Setting::updateOrCreate(['id' => 1], $data);

        $request->session()->flash('status', "setting update success"); 
        return redirect('/admin/setting');
    }

    public function notifiAllUser(Request $request)
    {
        $data = $request->except([]);
        $text_notifi = $request->message;
        $arr_id = User::pluck('id');
        notifi_system($text_notifi, $arr_id);
    }

    public function checkUpdateClaim(Request $request){
        $claims = Claim::where('code_claim_show',null)->orWhere('barcode', null)->pluck('code_claim')->toArray();
        $claims_chunk = array_chunk($claims, 500);
        foreach ($claims_chunk as $key => $value) {
            $HBS_CL_CLAIM = HBS_CL_CLAIM::whereIn('clam_oid',$value)->get();
            foreach ($HBS_CL_CLAIM as $key2 => $value2) {
                DB::table('claim')->where('code_claim',$value2->clam_oid)->update([
                    'code_claim_show' => $value2->cl_no,
                    'barcode' => $value2->barcode
                ]);
            }
        }
        $request->session()->flash('status', "setting update success"); 
        return redirect('/admin/setting');
    }

    public function checkUpdateLogApproved(Request $request){
        $claims = LogHbsApproved::where('MANTIS_ID',null)->orWhere('MEMB_NAME', null)->orWhere('POCY_REF_NO', null)->orWhere('MEMB_REF_NO', null)->pluck('cl_no')->toArray();
        $claims_chunk = array_chunk($claims, 500);
        foreach ($claims_chunk as $key => $value) {
            $HBS_CL_CLAIM = HBS_CL_CLAIM::whereIn('cl_no',$value)->get();
            foreach ($HBS_CL_CLAIM as $key2 => $value2) {
                DB::table('log_hbs_approved')->where('cl_no',$value2->cl_no)->update([
                    'MANTIS_ID' => $value2->barcode,
                    'MEMB_NAME' => $value2->MemberNameCap,
                    'POCY_REF_NO' => $value2->police->pocy_ref_no,
                    'MEMB_REF_NO' => $value2->member->memb_ref_no
                ]);
            }
        }
        $request->session()->flash('status', "setting update success"); 
        return redirect('/admin/setting');
    }

    public function updateBenhead(Request $request){
        $HBS_PD_BEN_HEAD = HBS_PD_BEN_HEAD::whereNotNull('BEN_HEAD')->whereIn('scma_oid_ben_type',['BENEFIT_TYPE_LF','BENEFIT_TYPE_IP','BENEFIT_TYPE_OP','BENEFIT_TYPE_BA','BENEFIT_TYPE_PA'])->with('PD_BEN_HEAD_LANG')->get();
        foreach ($HBS_PD_BEN_HEAD as $key => $value) {
            $HbsBenhead = HbsBenhead::updateOrCreate([
                'code'   => $value->ben_head,
            ],[
                'desc_vn'     => $value->PD_BEN_HEAD_LANG->code_desc_vn,
                'desc_en'     => $value->PD_BEN_HEAD_LANG->code_desc,
                'ben_type'    => str_replace('BENEFIT_TYPE_',"",$value->scma_oid_ben_type),
                'name'     => numberToRomanRepresentation($value->ben_head)
            ]);
        }
        $request->session()->flash('status', "setting update success"); 
        return redirect('/admin/setting');
    }


    public function getMessageMail(Request $request){
        $client = \Webklex\IMAP\Facades\Client::account('default');

        //Connect to the IMAP Server
        $client->connect();

        //Get all Mailboxes
        /** @var \Webklex\PHPIMAP\Support\FolderCollection $folders */
        $folders = $client->getFolders();
        //dd($folders);
        //Loop through every Mailbox
        /** @var \Webklex\PHPIMAP\Folder $folder */
        foreach($folders as $folder){

            //Get all Messages of the current Mailbox $folder
            /** @var \Webklex\PHPIMAP\Support\MessageCollection $messages */
            $messages = $folder->messages()->all()->get();
            dd($messages);
            /** @var \Webklex\PHPIMAP\Message $message */
            foreach($messages as $message){
                echo $message->getSubject().'<br />';
                echo 'Attachments: '.$message->getAttachments()->count().'<br />';
                echo $message->getHTMLBody();
                
                //Move the current Message to 'INBOX.read'
                if($message->move('INBOX.read') == true){
                    echo 'Message has ben moved';
                }else{
                    echo 'Message could not be moved';
                }
            }
        }
    }

}
