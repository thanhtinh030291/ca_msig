<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGroupUserRequest;
use App\Http\Requests\UpdateGroupUserRequest;
use App\GroupUser;
use Auth;
use App\User;
use Illuminate\Http\Request;
use Flash;
use Response;
use Illuminate\Support\Arr;

class GroupUserController extends Controller
{
    /** @var  GroupUserRepository */
    private $groupUserRepository;

    public function __construct()
    {
        //$this->authorizeResource(GroupUser::class);
        parent::__construct();
    }

    /**
     * Display a listing of the GroupUser.
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

        $groupUsers =  GroupUser::findByParams($search_params)->orderBy('id', 'desc');
        $groupUsers  = $groupUsers->paginate($per_page);

        return view('group_users.index', compact('search_params', 'admin_list', 'limit', 'limit_list', 'groupUsers' ));           
    }

    /**
     * Show the form for creating a new GroupUser.
     *
     * @return Response
     */
    public function create()
    {

        $list_user = User::getListIncharge();
        $list_user_non_group = User::where('group',null)->get();
        $list_user_of_group = [];
        return view('group_users.create', compact('list_user', 'list_user_non_group', 'list_user_of_group'));
    }

    /**
     * Store a newly created GroupUser in storage.
     *
     * @param CreateGroupUserRequest $request
     *
     * @return Response
     */
    public function store(CreateGroupUserRequest $request)
    {
        $data = $request->except([]);
        $userId = Auth::User()->id;
        $data['created_user'] = $userId;
        $data['updated_user'] = $userId;
        $rp = GroupUser::create($data);
        User::where('group', $rp->id)->update(['group' => null]);
        User::whereIn('id', explode(",",$data['_user']) )->update(['group' => $rp->id]);
        $request->session()->flash('status', 'Group User saved successfully.');

        return redirect(route('groupUsers.index'));
    }

    /**
     * Display the specified GroupUser.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show(GroupUser $groupUser)
    {
        
        return view('group_users.show', compact('groupUser'));
    }

    /**
     * Show the form for editing the specified GroupUser.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit(GroupUser $groupUser)
    {
        $list_user = User::getListIncharge();
        $list_user_non_group = User::where('group',null)->get();
        $list_user_of_group = User::where('group',$groupUser->id)->get();
        return view('group_users.edit',  compact('groupUser','list_user','list_user_non_group','list_user_of_group' ));
    }

    /**
     * Update the specified GroupUser in storage.
     *
     * @param int $id
     * @param UpdateGroupUserRequest $request
     *
     * @return Response
     */
    public function update(GroupUser $groupUser, UpdateGroupUserRequest $request)
    {
        $data = $request->except([]);
        $userId = Auth::User()->id;
        $data['updated_user'] = $userId;
        GroupUser::updateOrCreate(['id' => $groupUser->id], $data);
        
        User::where('group', $groupUser->id)->update(['group' => null]);
        User::whereIn('id', explode(",",$data['_user']) )->update(['group' => $groupUser->id]);

        $request->session()->flash('status', 'Group User updated successfully.'); 
        return redirect(route('groupUsers.index'));
    }

    /**
     * Remove the specified GroupUser from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(GroupUser $groupUser)
    {
        User::where('group', $groupUser->id)->update(['group' => null]);
        $groupUser->delete();
        return redirect(route('groupUsers.index'))->with('status', 'Group User deleted successfully.');
    }
}
