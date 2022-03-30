<?php

namespace App\Console\Commands;
use App\Claim;
use App\PaymentHistory;
use App\FinishAndPay;
use App\MANTIS_CUSTOM_FIELD_STRING;
use App\MANTIS_CUSTOM_FIELD;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class CheckFinishAndPay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:CheckFinishAndPay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        dump("JoBName : CheckFinishAndPay");
        dump("start : " . Carbon::now());
        //check Finish
        $dt = Carbon::now();
        $dt_check  = $dt->subDays(10)->format('Y-m-d h:i:s');
        $FinishAndPay = FinishAndPay::join('claim','claim.id','=','claim_id')->where('claim_type',"M")->where('notify',1)->where('finished', 0)->pluck('finish_and_pay.mantis_id')->toArray();
        $ready_to_pay_id = MANTIS_CUSTOM_FIELD::where('name','Pay Claim')->first()->id;
        $finished = MANTIS_CUSTOM_FIELD_STRING::whereIn('bug_id',$FinishAndPay)
        ->where('field_id', $ready_to_pay_id) // Has Payment Info
        ->where('value','Yes')
        ->pluck('bug_id')->toArray();
        if(!empty($finished)){
            FinishAndPay::whereIn('mantis_id',$finished)->update(['finished' => 1]);
        }

        //check pay
        $non_pay = FinishAndPay::where('notify',1)->where('finished', 1)->where('pay_time', 1)->where('payed', 0)->pluck('cl_no')->toArray();
        $history = PaymentHistory::whereIn('CL_NO', $non_pay)->pluck('CL_NO')->toArray();
        FinishAndPay::whereIn('cl_no', $history)->update(['payed' => 1]);
        $confirm_id = MANTIS_CUSTOM_FIELD::where('name','Has Payment Info')->first()->id;
        $non_pay_many = FinishAndPay::where('notify',1)->where('finished', 1)->where('pay_time','!=', 1)->where('payed', 0)->get();

        foreach ($non_pay_many as $key => $value) {
            $t = PaymentHistory::where('claim_id', $value->claim_id)->where('APP_AMT',$value->approve_amt)->count();
            if($t > 0){
                FinishAndPay::where('cl_no', $value->cl_no)->update(['payed' => 1]);
            }
        }

        // update custom confirm (quá hạn 3 ngày)
        $time_t = Carbon::now()->subDays(3)->toDateTimeString();
        $client_approved_id = MANTIS_CUSTOM_FIELD::where('name','Client Approved')->first()->id;
        $overtime = FinishAndPay::join('claim','claim.id','=','claim_id')->where('claim_type',"M")->where('notify',1)->where('date_tbbt',"<=", $time_t)->whereNotNull('date_tbbt')->pluck('finish_and_pay.mantis_id')->toArray();
        $mt_ov = MANTIS_CUSTOM_FIELD_STRING::whereIn('bug_id',$overtime)
        ->where('field_id', $client_approved_id) 
        ->where(function ($query) {
            $query->where('value','')
            ->orWhere('value', null)->orWhere('value', "No");
        })
        ->pluck('bug_id')->toArray();
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => config('constants.token_mantic'),
        ];
        foreach ($mt_ov as $key => $value) {
            try {
                DB::beginTransaction();
                $client = new \GuzzleHttp\Client([
                    'headers' => $headers
                ]);
                $response = $client->request("PATCH", config('constants.url_mantic_api') . "api/rest/issues/$value", ['form_params' => [
                    "custom_fields" => [
                        [
                            "field" => [
                                'name' => 'Client Approved'
                            ],
                            "value" => "Client Timeout"
                        ]
                    ]
                ]]);
                FinishAndPay::where('mantis_id', $value)->update(['date_tbbt' => null]);
                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
            }
        }
        
        dump("End : " . Carbon::now());
    }
}
