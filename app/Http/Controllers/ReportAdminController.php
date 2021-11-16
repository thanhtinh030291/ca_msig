<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateReportAdminRequest;
use App\Http\Requests\UpdateReportAdminRequest;
use App\ReportAdmin;
use Auth;
use App\User;
use Illuminate\Http\Request;
use Flash;
use Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ReportAdminController extends Controller
{
    /** @var  ReportAdminRepository */
    private $reportAdminRepository;

    public function __construct()
    {
        //$this->authorizeResource(ReportAdmin::class);
        parent::__construct();
    }

    /**
     * Display a listing of the ReportAdmin.
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

        $reportAdmins =  ReportAdmin::select('RECEIVE_DATE' , DB::raw('count(*) as total') )->groupBy('RECEIVE_DATE')->orderBy('RECEIVE_DATE', 'desc')->get();
        $reportAdmins  = $reportAdmins->paginate($per_page);

        return view('report_admins.index', compact('search_params', 'admin_list', 'limit', 'limit_list', 'reportAdmins' ));           
    }

    /**
     * Show the form for creating a new ReportAdmin.
     *
     * @return Response
     */
    public function create()
    {
        return view('report_admins.create');
    }

    /**
     * Store a newly created ReportAdmin in storage.
     *
     * @param CreateReportAdminRequest $request
     *
     * @return Response
     */
    public function store(CreateReportAdminRequest $request)
    {
        $userId = Auth::User()->id;
        $data = $request->except([]);
        $data['created_user'] = $userId;
        $data['updated_user'] = $userId;

        ReportAdmin::create($data);
        $request->session()->flash('status', 'Report Admin saved successfully.');

        return redirect(route('reportAdmins.index'));
    }

    /**
     * Display the specified ReportAdmin.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($datereportAdmin)
    {
        $date = \Carbon\Carbon::createFromFormat('d-m-Y', $datereportAdmin);
        $reportAdmin = ReportAdmin::select("report_admin.*", "claim.barcode", "claim.id")->join('claim', 'claim.id', '=', 'claim_id')->where('RECEIVE_DATE', $date->toDateString())->get();
        $admin_list = User::getListIncharge();
        return view('report_admins.show', compact('reportAdmin','admin_list'));
    }

    /**
     * Show the form for editing the specified ReportAdmin.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit(ReportAdmin $reportAdmin)
    {
        
        return view('report_admins.edit',  compact('reportAdmin'));
    }

    /**
     * Update the specified ReportAdmin in storage.
     *
     * @param int $id
     * @param UpdateReportAdminRequest $request
     *
     * @return Response
     */
    public function update(ReportAdmin $reportAdmin, UpdateReportAdminRequest $request)
    {
        $data = $request->except([]);
        $userId = Auth::User()->id;
        $data['updated_user'] = $userId;
        ReportAdmin::updateOrCreate(['id' => $reportAdmin->id], $data);

        $request->session()->flash('status', 'Report Admin updated successfully.'); 
        return redirect(route('reportAdmins.index'));
    }

    /**
     * Remove the specified ReportAdmin from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(ReportAdmin $reportAdmin)
    {
        $reportAdmin->delete();
        return redirect(route('reportAdmins.index'))->with('status', 'Report Admin deleted successfully.');
    }
}
