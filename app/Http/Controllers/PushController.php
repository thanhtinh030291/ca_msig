<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Auth;
use App\PushSubscriptions;

class PushController extends Controller
{
    use ValidatesRequests;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    //check exit notifi
    public function check_subscriptions(Request $request){
        $userId = Auth::User()->id;
        $endpoint = $request->endpoint;
        $publicKey = $request->publicKey;
        $authToken= $request->authToken;
        $count = PushSubscriptions::where('endpoint',$endpoint)
        ->where('public_key',$publicKey)
        ->where('auth_token',$authToken)
        ->where('subscribable_id',$userId)
        ->count();
        if($count > 0){
            return  1;
        }else{
            return  0;
        }

    }

    /**
     * Update user's subscription.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        
        $request->user()->updatePushSubscription(
            $request->endpoint,
            $request->publicKey,
            $request->authToken,
            $request->contentEncoding
        );

        return response()->json(null, 204);
    }

    /**
     * Delete the specified subscription.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $this->validate($request, ['endpoint' => 'required']);
        $request->user()->deletePushSubscription($request->endpoint);
        return response()->json(null, 204);
    }
}