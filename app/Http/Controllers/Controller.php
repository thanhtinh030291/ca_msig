<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Auth;
use App\User;
use App\PaymentHistory;
use App\FinishAndPay;
use View;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function __construct()
    {
        //its just a dummy data object.
        // Sharing is caring
        
        $this->middleware(function ($request, $next) {
            $user = Auth::user()  ;
            $listUser = User::pluck('email', 'id');
            $vision = 37;
            if($user){
                $messages = $user->messagesReceiver;
                $renewToClaim = PaymentHistory::where('notify_renew', 1)->where('created_user',$user->id)->get();
                $finishAndPay = FinishAndPay::where('notify',1)->where('user',$user->id)->where('finished',1)->where('payed',0)->get();
                //đếm bổ sung
                $now = time();
                $list_status = config('constants.status_mantic');
                $time_1 = $now - (60 * 60 * 24 * 3);
                $time_2 = $now - (60 * 60 * 24 * 10);
                try {
                    $MANTIS_BUG =\App\MANTIS_BUG::with('history_status')
                    ->where(function($query) use ($time_2, $list_status){
                        $query->whereHas('history_status', function($q) use($time_2){
                            $q->where('date_modified', "<=", $time_2);
                        })
                        ->where('status',array_search('inforequest',$list_status));
                    })
                    
                    ->orWhere(function($query) use ($time_1, $list_status){
                        
                        $query->whereNotIn('status',
                                [
                                    array_search('inforequest',$list_status),
                                    array_search('inforeceived',$list_status),
                                    array_search('accepted',$list_status),
                                    array_search('partiallyaccepted',$list_status),
                                    array_search('declined',$list_status),
                                    array_search('closed',$list_status)
                                ]
                        )
                        ->where('date_submitted',"<=",$time_1);
                    })
                    ->whereHas('user_hander', function($q) use($user){
                        $q->where('email', $user->email);
                    })
                    ->get();
                    $extendClaim = $MANTIS_BUG->map(function ($item, $key) use ($now, $list_status) {
                        if($item->status == array_search('inforequest',$list_status)){
                            $diff_date = $now - $item->history_status->date_modified;
                            $diff_date = (int)($diff_date / (60 * 60 * 24));
                            return [
                                'diff_date' => $diff_date,
                                'text' => "Quá thời gian bổ sung 10 ngày",
                                'mantis_id' => $item->id
                            ];
                        }else{
                            $diff_date = $now - $item->date_submitted;
                            $diff_date = (int)($diff_date / (60 * 60 * 24));
                            return [
                                'diff_date' => $diff_date,
                                'text' => "Quá thời gian xữ lý 3 ngày",
                                'mantis_id' => $item->id
                            ];

                        }
                        
                    });
                } catch (\Throwable $th) {
                    $extendClaim = [
                        0 =>[
                            'diff_date' => 00000000,
                            'text' => "Kết nối Etalk không hoạt động",
                            'mantis_id' => 00009999
                        ]
                    ];
                }
                View::share('extendClaim', $extendClaim);
                View::share('renewToClaim', $renewToClaim);
                View::share('finishAndPay', $finishAndPay);
                View::share('messages', $messages);
                View::share('listUser', $listUser);
                View::share('vision', $vision);
            }
            
            return $next($request);
        });
        
        
        
    }
}
