<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pusher\Pusher;
use Auth;
use App\User;
use App\Message;
use App\Events\Notify;
use App\Notifications\PushNotification;
use Illuminate\Support\Arr;

class SendMessageController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index(Request $request)
    {
        $search_params = [
            'is_read' => $request->get('is_read'),
            'message' => $request->get('search'),
            'important' => $request->get('important')
        ];
        
        $limit_list = config('constants.limit_list');
        $limit = $request->get('limit');
        $per_page = !empty($limit) ? $limit : Arr::first($limit_list);
        $user = Auth::user();
        $data = Message::findByParams($search_params)->where('user_to', $user->id)->latest();
        $admin_list = User::getListIncharge();
        $data  = $data->paginate($per_page);
        return view('messageManagement/index',compact('search_params', 'admin_list', 'limit', 'limit_list','data','admin_list'));
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Product  $product
    * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {   
        $where = array('id' => $id);
        $product  = Message::where($where)->first();
    
        return Response::json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Message::where('id',$id)->delete();
    
        return Response::json($product);
    }

    public function destroyMany(Request $request)
    {
        if(!empty($request->arr_delete)){
            Message::whereIn('id', explode(",",$request->arr_delete))->delete();
        }
        return back();
    }
    
    public function sent(Request $request)
    {
        $search_params = [
            'is_read' => $request->get('is_read'),
            'message' => $request->get('search'),
        ];
        $limit_list = config('constants.limit_list');
        $limit = $request->get('limit');
        $per_page = !empty($limit) ? $limit : Arr::first($limit_list);
        $user = Auth::user();
        $data = Message::findByParams($search_params)->where('user_from', $user->id)->latest();
        $admin_list = User::getListIncharge();
        $data  = $data->paginate($per_page);
        return view('messageManagement/index',compact('search_params', 'admin_list', 'limit', 'limit_list','data','admin_list'));
    }

    public function trash(Request $request)
    {
        $search_params = [
            'is_read' => $request->get('is_read'),
            'message' => $request->get('search'),
            'important' => $request->get('important')
        ];
        
        $limit_list = config('constants.limit_list');
        $limit = $request->get('limit');
        $per_page = !empty($limit) ? $limit : Arr::first($limit_list);
        $user = Auth::user();
        $data = Message::withTrashed()->where('user_to', $user->id)->where('deleted_at','!=',null)->latest();
        $admin_list = User::getListIncharge();
        $data  = $data->paginate($per_page);
        return view('messageManagement/index',compact('search_params', 'admin_list', 'limit', 'limit_list','data','admin_list'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $admin_list = User::getListIncharge();
        $data = Message::where('id', $id)->where(function ($query) use($user) {
            $query->where('user_from',  $user->id)
                ->orWhere('user_to', $user->id);
        })->first();
        
        if($data == null){
            return abort(404);
        }
        if($user->id == $data->user_to){
            $data->is_read = 1;
            $data->save();
        }
        
        return view('messageManagement.show', compact('data','admin_list'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'user' => 'required',
            'content' => 'required'
        ]);
        $user = Auth::user();
        $data['title'] = $user->name . ' g???i tin cho b???n';
        $data['content'] = $request->input('content');
        $data['avantar'] = config('constants.avantarStorage').'thumbnail/'.$user->avantar;
        $mesage_data = $user->messagesSent()->create([
            'user_to' => $request->input('user'),
            'message' => $request->input('content')
        ]);
        
        $user_to = User::findOrfail($request->input('user'));
        
        $SERVER_API_KEY = config('constants.SERVER_API_KEY');
        $data = [
            "registration_ids" => [$user_to->device_token],
            "notification" => [
                "title" => $data['title'],
                "body" => strip_tags(html_entity_decode($data['content'])),
                "icon"=>  $data['avantar'],
                'click_acction' => url('admin/message').'/'.$mesage_data->ids
                
            ],
            "webpush"=> [
                "fcm_options" => [
                    "link" => url('admin/message').'/'.$mesage_data->ids
                ]
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
        
        return redirect('/admin/home/');
    }

    public function readAll(Request $request){
        
        $user = Auth::user();
        Message::where('is_read' , 0)->where('user_to',$user->id)->update(['is_read' => 1]);
    }

    public function important(Request $request){
        
        Message::where('id' , $request->id)->update(['important' => $request->value]);
        return 1;
    }
}