<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateReportAdminRequest;
use App\Http\Requests\UpdateReportAdminRequest;
use App\ReportAdmin;
use App\CPS_VNBT_SHEETS;
use App\CPS_VCBSHEETS;
use App\CPS_PAYMENTS;
use Auth;
use App\User;
use Illuminate\Http\Request;
use Flash;
use Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\HBS_PV_PROVIDER;
use App\HBS_CL_CLAIM;

class ReportGopController extends Controller
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
            'prov_name' => $request->get('prov_name'),
            'tf_date_from' => $request->get('tf_date_from'),
            'tf_date_to' => $request->get('tf_date_to'),
            'tf_amt' => $request->get('tf_amt'),
        ];
        $admin_list = User::getListIncharge();
        $limit_list = config('constants.limit_list');
        $limit = $request->get('limit');
        $per_page = !empty($limit) ? $limit : Arr::first($limit_list);
        $HBS_PV_PROVIDER = HBS_PV_PROVIDER::where('cl_pay_acct_no','!=', null)->pluck('prov_name','cl_pay_acct_no');
        $condition = function ($q) {

        };
        $reportGop =  CPS_VCBSHEETS::with(['vnbt_sheets' => $condition])->orderBy('VCBS_ID', 'desc');
        if ($request->prov_name != null) {
            $pattern = '/[^0-9]+/';
            $string  = preg_replace($pattern, "", $request->prov_name);
            $reportGop =  $reportGop->where('BEN_ACCT',$string);
        }
        if ($request->tf_amt != null) {
            $pattern = '/[^0-9]+/';
            $string  = preg_replace($pattern, "", $request->tf_amt);
            $reportGop =  $reportGop->where('AMT',$string);
        }
        if($request->prov_name == null && $request->tf_amt == null){
            $arr = $HBS_PV_PROVIDER->map(function ($item, $key) {
                return preg_replace('/[^0-9]+/', "", $key);
            });

            $reportGop =  $reportGop->whereIn('BEN_ACCT',array_values($arr->toArray()));
        }
        $reportGop  = $reportGop->paginate($per_page);
        return view('report_gop.index', compact('search_params', 'admin_list', 'limit', 'limit_list', 'reportGop' , 'HBS_PV_PROVIDER' ));           
    }

    /**
     * Show the form for creating a new ReportAdmin.
     *
     * @return Response
     */
    public function create()
    {
        return view('report_gop.create');
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

        return redirect(route('reportGop.index'));
    }

    /**
     * Display the specified ReportAdmin.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($VCBS_ID)
    {
        $condition = function ($q) {

        };
        $reportGop =  CPS_VCBSHEETS::with(['vnbt_sheets' => $condition])->where('VCBS_ID', $VCBS_ID)->first();
        $PAYM_IDS = explode(",",$reportGop->PAYM_IDS);
        $reportAdmin = CPS_PAYMENTS::whereIn('PAYM_ID',$PAYM_IDS)->get();
        $admin_list = User::getListIncharge();
        return view('report_gop.show', compact('reportAdmin','admin_list'));
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
        
        return view('report_gop.edit',  compact('reportAdmin'));
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
        return redirect(route('reportGop.index'));
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
        return redirect(route('reportGop.index'))->with('status', 'Report Admin deleted successfully.');
    }
}
