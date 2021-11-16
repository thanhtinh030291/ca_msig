<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLevelRoleStatusRequest;
use App\Http\Requests\UpdateLevelRoleStatusRequest;
use App\LevelRoleStatus;
use Auth;
use App\User;
use Illuminate\Http\Request;
use Flash;
use Response;
use Illuminate\Support\Arr;
use App\RoleChangeStatus;

class LevelRoleStatusController extends Controller
{
    /** @var  LevelRoleStatusRepository */
    private $levelRoleStatusRepository;

    public function __construct()
    {
        //$this->authorizeResource(LevelRoleStatus::class);
        parent::__construct();
    }

    /**
     * Display a listing of the LevelRoleStatus.
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

        $levelRoleStatuses =  LevelRoleStatus::findByParams($search_params)->orderBy('id', 'desc');
        $levelRoleStatuses  = $levelRoleStatuses->paginate($per_page);
        $list_status = RoleChangeStatus::pluck('name','id');

        return view('level_role_statuses.index', compact('search_params', 'admin_list', 'limit', 'limit_list', 'levelRoleStatuses', 'list_status' ));           
    }

    /**
     * Show the form for creating a new LevelRoleStatus.
     *
     * @return Response
     */
    public function create()
    {
        $list_status = RoleChangeStatus::pluck('name','id');
        return view('level_role_statuses.create', compact('list_status'));
    }

    /**
     * Store a newly created LevelRoleStatus in storage.
     *
     * @param CreateLevelRoleStatusRequest $request
     *
     * @return Response
     */
    public function store(CreateLevelRoleStatusRequest $request)
    {
        $userId = Auth::User()->id;
        $data = $request->except([]);
        $data['created_user'] = $userId;
        $data['updated_user'] = $userId;

        LevelRoleStatus::create($data);
        $request->session()->flash('status', 'Level Role Status saved successfully.');

        return redirect(route('levelRoleStatuses.index'));
    }

    /**
     * Display the specified LevelRoleStatus.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show(LevelRoleStatus $levelRoleStatus)
    {
        $list_status = RoleChangeStatus::pluck('name','id');
        return view('level_role_statuses.show', compact('levelRoleStatus', 'list_status'));
    }

    /**
     * Show the form for editing the specified LevelRoleStatus.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit(LevelRoleStatus $levelRoleStatus)
    {
        $list_status = RoleChangeStatus::pluck('name','id');
        return view('level_role_statuses.edit',  compact('levelRoleStatus', 'list_status'));
    }

    /**
     * Update the specified LevelRoleStatus in storage.
     *
     * @param int $id
     * @param UpdateLevelRoleStatusRequest $request
     *
     * @return Response
     */
    public function update(LevelRoleStatus $levelRoleStatus, UpdateLevelRoleStatusRequest $request)
    {
        $data = $request->except([]);
        $userId = Auth::User()->id;
        $data['updated_user'] = $userId;
        LevelRoleStatus::updateOrCreate(['id' => $levelRoleStatus->id], $data);

        $request->session()->flash('status', 'Level Role Status updated successfully.'); 
        return redirect(route('levelRoleStatuses.index'));
    }

    /**
     * Remove the specified LevelRoleStatus from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(LevelRoleStatus $levelRoleStatus)
    {
        $levelRoleStatus->delete();
        return redirect(route('levelRoleStatuses.index'))->with('status', 'Level Role Status deleted successfully.');
    }
}
