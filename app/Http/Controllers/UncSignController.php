<?php

namespace App\Http\Controllers;
use App\User;
use Auth;
use App\UncSign;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;


class UncSignController extends Controller
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
            'name' => $request->get('name'),
            // 'created_user' => $request->get('created_user'),
            // 'created_at' => $request->get('created_at'),
            // 'updated_user' => $request->get('updated_user'),
            // 'updated_at' => $request->get('updated_at'),
        ];
        $UncSign = UncSign::findByParams($data['search_params'])->orderBy('id', 'desc');
        $data['admin_list'] = User::getListIncharge();
        //pagination result
        $data['limit_list'] = config('constants.limit_list');
        $data['limit'] = $request->get('limit');
        $per_page = !empty($data['limit']) ? $data['limit'] : Arr::first($data['limit_list']);
        $data['data']  = $UncSign->paginate($per_page);
        
        return view('UncSignManagement.index', $data);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $userId = Auth::User()->id;
        $UncSign = UncSign::findOrFail($id);
        $mpdf = new \Mpdf\Mpdf(['tempDir' => base_path('resources/fonts/')]);
        $fileName = storage_path("app/public/unc_sign")."/". $UncSign->url_non_sign;
        $file_name_new = md5(time());
        $patch_file_unc = storage_path("app/public/unc_sign")."/". $file_name_new .".pdf";
        $pagesInFile = $mpdf->SetSourceFile($fileName);
        $sign = '<div style="position: absolute; right: 5px; bottom: 0px;">' . getUserSign($userId) .'</div>';
        for ($i = 1; $i <= $pagesInFile; $i++) {
            $mpdf->AddPage('L');
            $tplId = $mpdf->ImportPage($i);
            $mpdf->UseTemplate($tplId);
            $mpdf->WriteHTML($sign);
            
        }
        $mpdf->Output($patch_file_unc, 'F');
        
        $data['url_signed'] = $file_name_new.".pdf";
        $data['status'] = 1;
        
        $token = getTokenCPS();
        $headers = [
            'Content-Type' => 'application/json',
        ];
        $body = [
            'access_token' => $token,
            'url_file' => asset('storage/unc_sign') ."/".$file_name_new.".pdf",
            'id' => $UncSign->group_unc_id,
        ];
        
        $client = new \GuzzleHttp\Client([
            'headers' => $headers
        ]);
        $response = $client->request("POST", config('constants.api_cps').'send_unc' , ['form_params'=>$body]);
        $response =  json_decode($response->getBody()->getContents());
        switch (data_get($response,'code')) {
            case '00':
                
                $app_name  = config('constants.appName');
                $app_email = config('constants.appEmail');
                $to_user = Setting::findOrFail(1)->finance_email;
                $subject = 'Ủy nhiệm chi đã ký';
                $template = 'templateEmail.uncSignedTemplate';
                Mail::send(
                    $template, [], function ($mail) use ($to_user, $app_name, $app_email, $subject) {
                        $mail->from($app_email, $app_name)
                            ->to($to_user, 'Finance')
                            ->subject($subject);
                    }
                );
                UncSign::updateOrCreate(['id' => $id], $data);
                $request->session()->flash('status', "Signed Success");
                return redirect('/admin/uncSign');
                break;
            default:
                return redirect('/admin/uncSign')->with('errorStatus', 'System error !!! please try again');
                break;
        }
        
    }

}
