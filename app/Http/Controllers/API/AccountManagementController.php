<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\UserAPIRequest;
use App\Password_resets;
use App\User;
use Carbon\Carbon;
use Config;
use DB;
use Hash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Order_details;

class AccountManagementController extends Controller
{
    

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);

    }
    public function login(UserAPIRequest $request)
    {
        $credentials = $request->only('email', 'password');        
        if ($token = auth('api')->attempt($credentials)) {
            $data = $this->respondWithToken($token);
            
            return response()->json(['status' => 'success', 'message' => __('web.successful_login'), 'data' => $data], 200);
            
        }
        return response()->json(['status' => 'error', 'message' => __('web.incorrect')], 401);

    }
    public function getAuthUser()
    {
        $user = JWTAuth::user();
        return response()->json(['status' => 'success', 'user' => $user], 200);
    }


    public function logout()
    {
        auth('api')->logout();
        return response()->json(['status' => 'success', 'message' => __('web.logout_success')], 200);
    }



    public function refresh()
    {
        return response(JWTAuth::getToken(), Response::HTTP_OK);
    }

    public function guard()
    {
        return Auth::guard();
    }
    protected function respondWithToken($token)
    {
        return ([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }
}
