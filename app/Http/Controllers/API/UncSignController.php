<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\API\UncSignAPIRequest;
use App\User;
use Carbon\Carbon;
use Config;
use DB;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Storage;
use App\UncSign;
use Illuminate\Support\Facades\File;
use App\Setting;
use App\Http\Controllers\SendMessageController;
class UncSignController extends Controller
{
    

    public function __construct()
    {
        $this->middleware('auth:api', []);
        if (!File::exists(storage_path("app/public/unc_sign")))
        {
            File::makeDirectory(storage_path("app/public/unc_sign"), 0777, true, true);
        }
    }

    public function requestSign(UncSignAPIRequest $request)
    {
        
        $url = $request->url_file_sign;
        $name = $request->name;
        $group_unc_id =   $request->group_unc_id;
        try {
            $file = file_get_contents($url);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => 'File not Found'], 401);
        }
        
        $file_name =  md5($name.time()) . '.pdf';
        file_put_contents(storage_path("app/public/unc_sign")."/".$file_name, $file);
        $unc = UncSign::create([
            'name' => $name,
            'url_non_sign' => $file_name,
            'status' => 0,
            'group_unc_id' => $group_unc_id,
        ]);
        $to_user = Setting::findOrFail(1)->manager_claim;
        $user = User::findOrFail(data_get($to_user,0,1));
        
        sendEmail($user, [], 'templateEmail.uncTemplate' , 'Yêu Cầu ký ủy nhiệm chi');
        return response()->json(['status' => 'success', 'message' => 'success'], 200);
    }
    
}
