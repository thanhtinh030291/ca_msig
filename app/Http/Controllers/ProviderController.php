<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProviderRequest;
use App\Http\Requests\UpdateProviderRequest;
use App\Provider;
use App\DeductProvider;
use Auth;
use App\User;
use App\HBS_CL_CLAIM;
use Illuminate\Http\Request;
use Flash;
use Response;
use Illuminate\Support\Arr;

class ProviderController extends Controller
{
    /** @var  ProviderRepository */
    private $providerRepository;

    public function __construct()
    {
        $this->authorizeResource(Provider::class);
        parent::__construct();
    }

    /**
     * Display a listing of the Provider.
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
            'PROV_CODE' => $request->get('PROV_CODE'),
            'PROV_NAME' => $request->get('PROV_NAME'),
        ];
        $admin_list = User::getListIncharge();
        $limit_list = config('constants.limit_list');
        $limit = $request->get('limit');
        $per_page = !empty($limit) ? $limit : Arr::first($limit_list);
        
        $providers =  Provider::findByParams($search_params)->orderBy('id', 'desc');
        $providers  = $providers->paginate($per_page);
        return view('providers.index', compact('search_params', 'admin_list', 'limit', 'limit_list', 'providers' ));           
    }

    /**
     * Show the form for creating a new Provider.
     *
     * @return Response
     */
    public function create()
    {
        return view('providers.create');
    }

    /**
     * Store a newly created Provider in storage.
     *
     * @param CreateProviderRequest $request
     *
     * @return Response
     */
    public function store(CreateProviderRequest $request)
    {
        $claim = HBS_CL_CLAIM::findOrFail($request->_clam_oid);
        $SumAppAmt = $claim->sumAppAmt;
        $sumDeductclaim = DeductProvider::where('claim_no',$request->_claim_no)->sum('amt');
        if($SumAppAmt < (removeFormatPrice($request->_amt) + $sumDeductclaim)){
            $request->session()->flash('errorStatus', 'Không thể ghi nợ số tiền lớn hơn Approve amt');
            return redirect(route('providers.create'));
        }
        $userId = Auth::User()->id;
        $data = $request->except(['PROV_CODE']);
        $data['created_user'] = $userId;
        $data['updated_user'] = $userId;

        $provider = Provider::updateOrCreate(['PROV_CODE' => $request->PROV_CODE], $data);
        $provider->deduct_provider()->create([
            'amt' => $request->_amt,
            'claim_no' => $request->_claim_no,
            'comment' => $request->_comment,
            'type' => 0,
            'created_user' => $userId,
            'updated_user' => $userId,
        ]);
        $request->session()->flash('status', 'Provider saved successfully.');

        return redirect(route('providers.index'));
    }

    /**
     * Display the specified Provider.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show(Provider $provider)
    {
        $log_deduct = $provider->deduct_provider;
        $totalAmt   = $log_deduct->sum('amt');
        $admin_list = User::getListIncharge();
        return view('providers.show', compact('provider','totalAmt', 'log_deduct', 'admin_list'));
    }

    /**
     * Show the form for editing the specified Provider.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit(Provider $provider)
    {
        
        return view('providers.edit',  compact('provider'));
    }

    /**
     * Update the specified Provider in storage.
     *
     * @param int $id
     * @param UpdateProviderRequest $request
     *
     * @return Response
     */
    public function update(Provider $provider, UpdateProviderRequest $request)
    {
        $data = $request->except([]);
        $userId = Auth::User()->id;
        $data['updated_user'] = $userId;
        Provider::updateOrCreate(['id' => $provider->id], $data);

        $request->session()->flash('status', 'Provider updated successfully.'); 
        return redirect(route('providers.index'));
    }

    /**
     * Remove the specified Provider from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(Provider $provider)
    {
        $provider->delete();
        return redirect(route('providers.index'))->with('status', 'Provider deleted successfully.');
    }
}
