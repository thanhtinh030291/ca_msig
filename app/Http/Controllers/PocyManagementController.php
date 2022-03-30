<?php

namespace App\Http\Controllers;
use App\User;
use App\PocyManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Validation\Rule;

class PocyManagementController extends Controller
{
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
            'pocy_ref_no' => $request->old('pocy_ref_no'),
        ];
        $pocyManagement = PocyManagement::findByParams($data['search_params'])->orderBy('id', 'desc');
        $data['list_provider'] = \App\HBS_PV_PROVIDER::pluck('prov_name','prov_oid')->toArray();
        $data['list_pocy'] = \App\HBS_MR_POLICY::whereNotNull('pocy_ref_no')->pluck('pocy_ref_no','pocy_ref_no')->toArray();
        
        $data['admin_list'] = User::getListIncharge();
        # pagination result
        $data['limit_list'] = config('constants.limit_list');
        $data['limit'] = $request->get('limit');
        $per_page = ! empty($data['limit']) ? $data['limit'] : Arr::first($data['limit_list']);
        $data['data']  = $pocyManagement->paginate($per_page);
        
        return view('pocyManagement.index', $data);
    }

   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data_n = $request->except([]);
        $company = $request->company;
        $validator = Validator::make($request->all(), [
            'pocy_ref_no' => [
                'required',
                'unique:pocy_management'
            ]
            
        ]);
        
        if ($validator->fails()) {
            $request->session()->flash('errorStatus', implode('<br />',$validator->errors()->all())); 
            return redirect('/admin/pocy')->withInput();   
        }
        
        if ( ! empty($request->_list_provider)) {
            $data_n['providers'] = implode(',', $request->_list_provider);
        }
        if ( ! empty($request->email)) {
            $data_n['email'] = $request->email;
        }
        
        PocyManagement::create($data_n);
        $request->session()->flash('status', __('message.create_success')); 
        
        return redirect('/admin/pocy');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = PocyManagement::findOrFail($id);
        $list_provider = \App\HBS_PV_PROVIDER::pluck('prov_name','prov_oid')->toArray();
        
        return view('pocyManagement.edit', compact('data','list_provider'));
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
        $data = $request->except([]);

        # Do update
        if ( ! empty($request->_list_provider)) {
            $data['providers'] = implode(',', $request->_list_provider);
        } else {
            $data['providers'] = null;
        }
        
        PocyManagement::updateOrCreate(['id' => $id], $data);
        
        $request->session()->flash('status', __('message.update_success')); 
        return redirect("/admin/pocy/$id/edit");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PlanHbsConfig $PlanHbsConfig)
    {
        dd(7);
        $data = $PlanHbsConfig;
        $data->delete();
        return redirect('/admin/PlanHbsConfig')->with('status', __('message.update_fail'));
    }
}
