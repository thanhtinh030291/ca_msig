<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTransactionRoleStatusRequest;
use App\Http\Requests\UpdateTransactionRoleStatusRequest;
use App\TransactionRoleStatus;
use App\LevelRoleStatus;
use Auth;
use App\User;
use Illuminate\Http\Request;
use Flash;
use Response;
use Illuminate\Support\Arr;
use App\Role;
use App\RoleChangeStatus;

class TransactionRoleStatusController extends Controller
{
    /** @var  TransactionRoleStatusRepository */
    private $transactionRoleStatusRepository;

    public function __construct()
    {
        //$this->authorizeResource(TransactionRoleStatus::class);
        parent::__construct();
    }

    /**
     * Display a listing of the TransactionRoleStatus.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {


        $data =  LevelRoleStatus::with('transaction_role_status')->get();
        $list_role = Role::pluck('name','id');
        $list_status = RoleChangeStatus::pluck('name','id');
        return view('transaction_role_statuses.index', compact('data','list_role','list_status' ));           
    }

    /**
     * Show the form for creating a new TransactionRoleStatus.
     *
     * @return Response
     */
    public function create()
    {
        return view('transaction_role_statuses.create');
    }

    /**
     * Store a newly created TransactionRoleStatus in storage.
     *
     * @param CreateTransactionRoleStatusRequest $request
     *
     * @return Response
     */
    public function store(CreateTransactionRoleStatusRequest $request)
    {
        $userId = Auth::User()->id;
        $id_level = $request->level_id;
        $data = LevelRoleStatus::findOrFail($id_level);
        $array_id_isset = $request->id ? array_filter($request->id) : [];
        //dell
        
            $dataDel = TransactionRoleStatus::where('level_role_status_id',$id_level);
            $dataDel->delete();
        // update
        $dataItemNew = []; 
        
        if($request->id){
            foreach ($request->id as $key => $value) {
                $dataItemNew[] = [
                    'level_role_status_id' => $id_level,
                    'current_status' => $request->current_status[$key],
                    'role' => $request->role[$key],
                    'to_status' => $request->to_status[$key],
                    'created_user' => $userId,
                    'updated_user' => $userId,
                ];
            }
        }
        // update
        $data->push();
        // new season price
        $data->transaction_role_status()->createMany($dataItemNew);
        $request->session()->flash('status', 'Transaction Role Status saved successfully.');
        return redirect(route('transactionRoleStatuses.index'));
    }

    /**
     * Display the specified TransactionRoleStatus.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show(TransactionRoleStatus $transactionRoleStatus)
    {
        
        return view('transaction_role_statuses.show', compact('transactionRoleStatus'));
    }

    /**
     * Show the form for editing the specified TransactionRoleStatus.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit(TransactionRoleStatus $transactionRoleStatus)
    {
        
        return view('transaction_role_statuses.edit',  compact('transactionRoleStatus'));
    }

    /**
     * Update the specified TransactionRoleStatus in storage.
     *
     * @param int $id
     * @param UpdateTransactionRoleStatusRequest $request
     *
     * @return Response
     */
    public function update(TransactionRoleStatus $transactionRoleStatus, UpdateTransactionRoleStatusRequest $request)
    {
        $data = $request->except([]);
        $userId = Auth::User()->id;
        $data['updated_user'] = $userId;
        TransactionRoleStatus::updateOrCreate(['id' => $transactionRoleStatus->id], $data);

        $request->session()->flash('status', 'Transaction Role Status updated successfully.'); 
        return redirect(route('transactionRoleStatuses.index'));
    }

    /**
     * Remove the specified TransactionRoleStatus from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(TransactionRoleStatus $transactionRoleStatus)
    {
        $transactionRoleStatus->delete();
        return redirect(route('transactionRoleStatuses.index'))->with('status', 'Transaction Role Status deleted successfully.');
    }
}
