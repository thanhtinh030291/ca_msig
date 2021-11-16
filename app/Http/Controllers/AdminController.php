<?php

namespace App\Http\Controllers;

use App\User;
use App\Role;
use App\Permission;
use App\Http\Requests\AdminRequest;
use Auth;
use Config;
use Illuminate\Http\Request;
use Image;
use Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function __construct() {
		// $this->middleware(function ($request, $next) {
		// 	$id = Auth::user()->id;
		// 	if ($id == 1) {
		// 		return $next($request);
		// 	} else {
		// 		return response()->make(view('adminManagement.error'), 404);
		// 	}

		// });
		parent::__construct();
	}
	public function index(Request $request) {

		$itemPerPage = Config::get('constants.paginator.itemPerPage');
		$admin = Config::get('constants.roles.Admin');
		$email = $request->get('searchEmail');
		$name = $request->get('searchName');
		$status = $request->get('searchStatus');
		$created_at = $request->get('searchCreated_at');

		$admins = User::orderBy('id', 'DESC')->where(function ($query) use ($email, $name, $status, $created_at) {
			if ($email != NULL) {
				$query->where('email', 'LIKE', '%' . $email . '%');
			}
			if ($name != NULL) {
				$query->where('name', 'LIKE', '%' . $name . '%');
			}
			if ($status != NULL) {
				$query->where('status', $status);
			}
			if ($created_at != NULL) {
				$query->where('created_at', '>=', date($created_at . ' 00:00:00'))->where('created_at', '<=', date($created_at . ' 23:59:59'));
			}
			
		})->paginate($itemPerPage);

		return view('adminManagement.index', compact('admins', 'email', 'name', 'status', 'created_at'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		$all_roles_in_database = Role::all()->pluck('name','name');
		return view('adminManagement.create', compact('all_roles_in_database'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(AdminRequest $request) {
		$dataNew = $request->except([]);
		
		$dataNew['password'] = bcrypt($dataNew['password']);
		try {
            DB::beginTransaction();
			$user = User::Create($dataNew);
			$user->assignRole($request->_role);
			$data['password'] =$request->password;
			$data['user'] = $user;
			sendEmail($user, $data, 'templateEmail.registerAcountTemplate' , 'Thông Tin Đăng Nhập Hệ Thống Claim Assistant');
			DB::commit();
			$request->session()->flash('status', __('message.add_account'));
			return redirect('/admin/admins/');
		} catch (Exception $e) {
			Log::error(generateLogMsg($e));
            DB::rollback(); $request->session()->flash(
                'errorStatus', 
                __('message.update_fail')
            );
            return redirect('/admin/admins/')->withInput();
        }
		
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		$user = User::findOrFail($id);
		$all_roles_in_database = Role::all()->pluck('name','name');
		$all_permissions_in_database = Permission::all()->pluck('name','name');
		return view('adminManagement.show', compact('user', 'all_roles_in_database', 'all_permissions_in_database' ));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		$user = User::with('roles')->findOrFail($id);
		$all_roles_in_database = Role::all()->pluck('name','name');
		return view('adminManagement.edit', compact('user', 'all_roles_in_database'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(AdminRequest $request, $id) {
		$request->validated();
		$dataNew = $request->except('profile_image', '_method', '_token');

		$user = User::updateOrCreate(['id' => $id], $dataNew);
		$user->syncRoles($request->_role);
		$request->session()->flash('status', __('message.update_account'));
		return redirect('/admin/admins');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$admin = User::findOrFail($id);
		$admin->delete();
		return redirect('/admin/admins')->with('status', __('message.delete_account'));
	}

}
