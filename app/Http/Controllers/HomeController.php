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
}
