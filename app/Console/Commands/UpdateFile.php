<?php

namespace App\Console\Commands;
use App\Claim;
use App\PaymentHistory;
use App\ExportLetter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class UpdateFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:UpdateFile';

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
        $dt = Carbon::now();
        $dt_check  = $dt->subDays(10)->format('Y-m-d h:i:s');
        $payments = PaymentHistory::where('update_file',0)->where('created_at','>=', $dt_check)->get();
        $path = '/public/payment/';
        foreach ($payments as $key => $value) {
            
            $ExportLetter = ExportLetter::where('claim_id',$value->claim_id)->where('approve',"!=",null)->where('apv_amt', trim($value->APP_AMT))->first();
            if(!empty($ExportLetter)){
                $letter = data_get($ExportLetter->approve,'data');
                if($letter){
                    $letter = "<html><body>" .$letter."</body></html>";
                    $value->url_letter = saveFileContent($letter,$path,'doc',$value->url_letter);
                }
                $payments = data_get($ExportLetter->approve,'data_payment');
                if($payments){
                    $value->url_payment = saveFileContent(base64_decode($payments),$path,'pdf',$value->url_payment);
                }
                $value->update_file = 1;
                $value->save();
            }
        }
        $this->info('Cron UpdateFile Run successfully!');
    }
}
