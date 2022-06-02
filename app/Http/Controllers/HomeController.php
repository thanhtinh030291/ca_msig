<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use Auth;
use App\Claim;
use Carbon\Carbon;
use Redis;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        
        $Ipclient = \Request::getClientIp(true);
        $listUser = User::whereNotIn('id',[Auth::User()->id])->pluck('email', 'id');
        $user = Auth::user()  ;
        $latestMessages = $user->messagesReceiver10;
        //dd($latestMessages);
        $sentMessages = $user->messagesSent10;
        $sumMember = User::count();
        $sumClaim  = Claim::count();
        $sumClaimToDate = Claim::whereDate('created_at', Carbon::today())->count();
        return view('home', compact('listUser','latestMessages','sentMessages','sumMember','sumClaim','sumClaimToDate', 'Ipclient'));
    }

    /** 
     * Write code on Method
     *
     * @return response()
     */
    public function saveToken(Request $request)
    {
        auth()->user()->update(['device_token'=>$request->token]);
        return response()->json(['token saved successfully.']);
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function sendNotification(Request $request)
    {
        $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();
        $SERVER_API_KEY = config('constants.SERVER_API_KEY');
        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $request->content,
                "body" => $request->content,
                "icon"=> asset("images/logo.png"),
                'click_action' => 'http://localhost/admin/home',
            ]
        ];
        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);

        return redirect()->back();
    }
}
