<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
use Auth;
use Illuminate\Support\Arr;


class PermissionController extends Controller
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
            'name' => $request->get('name'),
        ];
        $listData = Role::where('name','LIKE' , '%' .$data['search_params']['name'] . '%')->orderBy('id', 'desc');
        $data['admin_list'] = User::getListIncharge();
        //pagination result
        $data['limit_list'] = config('constants.limit_list');
        $data['limit'] = $request->get('limit');
        $per_page = !empty($data['limit']) ? $data['limit'] : Arr::first($data['limit_list']);
        $data['data']  = $listData->paginate($per_page);
        
        return view('roleManagement.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $all_permissions_in_database = Permission::all()->pluck('name','name');
        return view('roleManagement.create', compact('all_permissions_in_database'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userId = Auth::User()->id;
        $data = $request->except([]);
        $newdata = Role::create($data);
        $newdata->givePermissionTo($request->_permissions); 
        $request->session()->flash('status', __('message.create_success')); 
        
        return redirect('/admin/role');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Role::findOrFail($id);
        $permissions = $data->permissions()->pluck('name','name');
        
        $all_permissions_in_database = Permission::all()->pluck('name','name');

        return view('roleManagement.detail', compact('data', 'permissions', 'all_permissions_in_database'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Role::findOrFail($id);
        $permissions = $data->permissions()->pluck('id');
        $all_permissions_in_database = Permission::all()->pluck('name','id');
        return view('roleManagement.edit', compact('data', 'permissions', 'all_permissions_in_database'));
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
        $role = Role::updateOrCreate(['id' => $id], $data);
        $role->permissions()->sync($request->_permissions);

        $request->session()->flash('status', __('message.update_success')); 
        return redirect('/admin/role');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Role::findOrFail($id);
        $data->delete();
        return redirect('/admin/role')->with('status', __('message.delete_success'));
    }
}
