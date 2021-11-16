<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoleChangeStatusRequest;
use App\Http\Requests\UpdateRoleChangeStatusRequest;
use App\RoleChangeStatus;
use Auth;
use App\User;
use Illuminate\Http\Request;
use Flash;
use Response;
use Illuminate\Support\Arr;

class RoleChangeStatusController extends Controller
{
    /** @var  RoleChangeStatusRepository */
    private $roleChangeStatusRepository;

    public function __construct()
    {
        //$this->authorizeResource(RoleChangeStatus::class);
        parent::__construct();
    }

    /**
     * Display a listing of the RoleChangeStatus.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $search_params = [
            'created_user' => $request->get('created_user'),
            'created_at' => $request->get('created_at'),
            'updated_user' => $request->get('updated_user'),
            'updated_at' => $request->get('updated_at'),
        ];
        $admin_list = User::getListIncharge();
        $limit_list = config('constants.limit_list');
        $limit = $request->get('limit');
        $per_page = !empty($limit) ? $limit : Arr::first($limit_list);

        $roleChangeStatuses =  RoleChangeStatus::findByParams($search_params)->orderBy('id', 'desc');
        $roleChangeStatuses  = $roleChangeStatuses->paginate($per_page);

        return view('role_change_statuses.index', compact('search_params', 'admin_list', 'limit', 'limit_list', 'roleChangeStatuses' ));           
    }

    /**
     * Show the form for creating a new RoleChangeStatus.
     *
     * @return Response
     */
    public function create()
    {
        return view('role_change_statuses.create');
    }

    /**
     * Store a newly created RoleChangeStatus in storage.
     *
     * @param CreateRoleChangeStatusRequest $request
     *
     * @return Response
     */
    public function store(CreateRoleChangeStatusRequest $request)
    {
        $userId = Auth::User()->id;
        $data = $request->except([]);
        $data['created_user'] = $userId;
        $data['updated_user'] = $userId;

        RoleChangeStatus::create($data);
        $request->session()->flash('status', 'Role Change Status saved successfully.');

        return redirect(route('roleChangeStatuses.index'));
    }

    /**
     * Display the specified RoleChangeStatus.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show(RoleChangeStatus $roleChangeStatus)
    {
        
        return view('role_change_statuses.show', compact('roleChangeStatus'));
    }

    /**
     * Show the form for editing the specified RoleChangeStatus.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit(RoleChangeStatus $roleChangeStatus)
    {
        
        return view('role_change_statuses.edit',  compact('roleChangeStatus'));
    }

    /**
     * Update the specified RoleChangeStatus in storage.
     *
     * @param int $id
     * @param UpdateRoleChangeStatusRequest $request
     *
     * @return Response
     */
    public function update(RoleChangeStatus $roleChangeStatus, UpdateRoleChangeStatusRequest $request)
    {
        $data = $request->except([]);
        $userId = Auth::User()->id;
        $data['updated_user'] = $userId;
        RoleChangeStatus::updateOrCreate(['id' => $roleChangeStatus->id], $data);

        $request->session()->flash('status', 'Role Change Status updated successfully.'); 
        return redirect(route('roleChangeStatuses.index'));
    }

    /**
     * Remove the specified RoleChangeStatus from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(RoleChangeStatus $roleChangeStatus)
    {
        $roleChangeStatus->delete();
        return redirect(route('roleChangeStatuses.index'))->with('status', 'Role Change Status deleted successfully.');
    }


}
