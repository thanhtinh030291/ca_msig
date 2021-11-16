<?php

namespace App\Http\Controllers;
use App\User;
use Auth;
use App\PaymentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Exception;
use App\HBS_CL_CLAIM;


class PaymentHistoryController extends Controller
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
            'CL_NO' => $request->get('CL_NO'),
            'created_user' => $request->get('created_user'),
            'MEMB_REF_NO' => $request->get('MEMB_REF_NO'),
            'POCY_REF_NO' => $request->get('POCY_REF_NO'),
            'MEMB_REF_NO' => $request->get('MEMB_REF_NO'),
        ];
        $Product = PaymentHistory::findByParams($data['search_params'])->orderBy('id', 'desc');
        $data['search_params']['created_to'] = $request->get('created_to');
        $data['search_params']['created_from'] = $request->get('created_from');

        $created_from = $data['search_params']['created_from'] ? $data['search_params']['created_from']." 00:00:00" : "1991-01-01 00:00:00";
        $created_to = $data['search_params']['created_to'] ? $data['search_params']['created_to']." 23:59:59" : "2100-01-01 23:59:59";
        $Product = $Product->whereBetween('created_at' ,[$created_from,$created_to]);
        $data['sum_PRES_AMT'] = $Product->sum('PRES_AMT');
        $data['sum_APP_AMT'] = $Product->sum('APP_AMT');
        $data['sum_TF_AMT'] = $Product->sum('TF_AMT');
        $data['admin_list'] = User::getListIncharge();
        //pagination result
        $data['limit_list'] = config('constants.limit_list');
        $data['limit'] = $request->get('limit');
        $per_page = !empty($data['limit']) ? $data['limit'] : Arr::first($data['limit_list']);
        $data['data']  = $Product->paginate($per_page);
        
        return view('paymentHistoryManagement.index', $data);
    }

    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentHistory $PaymentHistory)
    {
        $data = $PaymentHistory;
        $userCreated = $data->userCreated->name;
        $userUpdated = $data->userUpdated->name;
        return view('paymentHistoryManagement.detail', compact('data', 'userCreated', 'userUpdated'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentHistory $PaymentHistory)
    {
        $data = $PaymentHistory;
        return view('paymentHistoryManagement.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PaymentHistory $PaymentHistory)
    {
        $data = $request->except([]);
        $userId = Auth::User()->id;
        $data['updated_user'] = $userId;
        PaymentHistory::updateOrCreate(['id' => $product->id], $data);

        $request->session()->flash('status', __('message.reason_inject_update_success')); 
        return redirect('/admin/PaymentHistory');
    }

    public function get_renewed_claim(){
        $token = getTokenCPS();
        $headers = [
            'Content-Type' => 'application/json',
        ];
        $body = [
            'access_token' => $token,
        ];

        $client = new \GuzzleHttp\Client([
            'headers' => $headers
        ]);
        $data = [];
        try {
            $response = $client->request("POST", config('constants.api_cps').'get_renewed_claim' , ['form_params'=>$body]);
            $response =  json_decode($response->getBody()->getContents());
            
            if(data_get($response,'code') == "00" && data_get($response,'data') != null){
                $data = data_get($response,'data');
            }
        } catch (Exception $e) {
            
        }
        return view('paymentHistoryManagement.renewed', compact('data'));
    }
}
