<?php
use Illuminate\Support\Str;
use App\User;
use App\Message;
use App\Setting;
use App\ItemOfClaim;
use App\Events\Notify;
use App\Notifications\PushNotification;
use Pusher\Pusher;
use Illuminate\Support\Facades\Storage;

function getUserSign(){ // for member claim 
    $Setting = Setting::findOrFail('1');
    $user = User::findOrFail($Setting->header_claim[0]);
    $dirStorage = config('constants.signarureStorage');
    $dataImage =  $dirStorage . $user->signarure ;
    $htm = "<span><img src='{$dataImage}' alt='face' height='120' width='140'></img><br/>
            $user->name
        </span>";
    return $htm;
}

function getUserSignThumb(){ // for provider claim
    $Setting = Setting::findOrFail('1');
    $user = User::findOrFail($Setting->header_claim[0]);
    $dirStorage = config('constants.signarureStorage');
    $dataImage =  $dirStorage . $user->signarure ;
    $htm = "<span><img src='{$dataImage}' alt='face' height='73' width='100'></img><br/>
            $user->name
        </span>";
    return $htm;
}

function getUserSignThumb_create($id){ // for provider claim
    
    $user = User::findOrFail($id);
    $dirStorage = config('constants.signarureStorage');
    $dataImage =  $dirStorage . $user->signarure ;
    $htm = "<span><img src='{$dataImage}' alt='face' height='73' width='100'></img><br/>
            $user->name
        </span>";
    return $htm;
}

function saveImage($file ,$path, $thumbnail=null){
    if (!File::exists(storage_path("app".$path)))
    {
        File::makeDirectory(storage_path("app".$path), 0777, true, true);
    }
    if (!File::exists(storage_path("app".$path."thumbnail/")))
    {
        File::makeDirectory(storage_path("app".$path."thumbnail/"), 0777, true, true);
    }
    $file_name =  md5($file->getClientOriginalName().time()) . '.' . $file->getClientOriginalExtension();
        $image = Image::make($file)
            ->resize(400,null,function ($constraint) {
                $constraint->aspectRatio();
                })
            ->save(storage_path("app".$path) . $file_name);
        if($thumbnail){
            $image->resize(90, 90)
            ->save(storage_path("app".$path."thumbnail/"). $file_name);
        }
            

    return $file_name;
}

function saveFile($file ,$path ,$oldFile = null)
{
    if($oldFile){
        Storage::delete($path.$oldFile);
    }
    $fileName = time() . md5($file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();
    $file->storeAs($path, $fileName);
    return $fileName;
}

function saveFileContent($file_content ,$path ,$ext,$oldFile = null)
{
    if($oldFile){
        Storage::delete($path.$oldFile);
    }
    $fileName = uniqid() . md5(microtime()) . '.' .$ext;
    Storage::put($path."/".$fileName, $file_content);
    return $fileName;
}


function GetApiMantic($url)
{
    $headers = [
        'Content-Type' => 'application/json',
        'Authorization' => config('constants.token_mantic'),
    ];
    
    try {
        $client = new \GuzzleHttp\Client([
            'headers' => $headers
        ]);
        $request = $client->get(config('constants.url_mantic_api').$url);
        $response = $request->getBody();
    }catch (GuzzleHttp\Exception\ClientException $e) {
        $response = $e->getResponse()->getBody(true);
    }
    
    
    return json_decode($response->getContents(), true);
}

//truncate string

function truncate($string , $limit = 100){
    return Str::limit($string, $limit);
}

function PostApiMantic($url,$body,$method = 'POST') {
    $headers = [
        'Content-Type' => 'application/json',
        'Authorization' => config('constants.token_mantic'),
    ];
    $client = new \GuzzleHttp\Client([
            'headers' => $headers
        ]);
    $response = $client->request($method, config('constants.url_mantic_api').$url , ['form_params'=>$body]);

    return $response;
}

function PostApiManticHasFile($url,$body) {
    $headers = [
        'Content-Type' => 'application/json',
        'Authorization' => config('constants.token_mantic'),
    ];
    $client = new \GuzzleHttp\Client([
            'headers' => $headers
        ]);
      

    $response = $client->request("POST", config('constants.url_mantic_api').$url , $body);

    return $response;
}

function sendEmail($user_send, $data , $template , $subject)
{
    if (!data_get($user_send, 'email')) {
        return false;
    }
    $app_name  = config('constants.appName');
    $app_email = config('constants.appEmail');
    Mail::send(
        $template, 
        [
            'user' => $user_send, 
            'data' => $data 
        ], function ($mail) use ($user_send, $app_name, $app_email, $subject) {
            $mail
                ->to($user_send->email, $user_send->name)
                ->subject($subject);
        }
    );
    return true;
}

function sendEmailProvider($user_send, $to_email , $to_name, $subject, $data , $template, $reply = null , $bcc = [])
{
    if (!data_get($user_send, 'email')) {
        return false;
    }
    $app_name  = config('constants.appName');
    $app_email = config('constants.appEmail');
    Mail::send(
        $template, 
        [
            'user' => $user_send, 
            'data' => isset($data) ?  $data : []
        ], function ($mail) use ($user_send, $to_email, $to_name, $subject, $app_name, $app_email, $data , $reply,$bcc) {
            $email_repply = $reply == null ? $user_send->email : $reply;
            $email_name = $reply == null ? $user_send->name : "Claim BSH";
            foreach ($bcc as $key => $value) {
                $mail->bcc($value, $value);
            }
            $mail
                ->to( $to_email )
                ->replyTo( $email_repply , $email_name)
                ->replyTo( 'cskh.msig@pacificcross.com.vn' , 'CSKH')
                ->attachData(base64_decode($data['attachment']['base64']), $data['attachment']['filename'], ['mime' => $data['attachment']['filetype']])
                ->subject($subject ."- #".config('constants.company')."#");
        }
    );
    return true;
}
// set active value
function setActive(string $path, $class = 'active') {
    $requestPath = implode('/', array_slice(Request::segments(), 0, 2));
    return $requestPath === $path ? $class : "";
}
/**
 * Get class name base on relative_url input & now request
 *
 * @param string $relative_url [normal use route('name', [], false)]
 * @param string $class        [class name when true path & active]
 *
 * @return string [$class or '']
 */
function setActiveByRoute(string $relative_url, $class = 'active') 
{
    $request_path = '/'. implode('/', request()->segments());
    return $request_path === $relative_url ? $class : "";
}

function loadImg($imageName = null, $dir = null) {
    if (strlen(strstr($imageName, '.')) > 0) {
        return $dir . $imageName;
    } else {
        return '/images/noimage.png';
    }
}

function loadAvantarUser($avantar){
    if($avantar == 'admin.png'){
        return '/images/noimage.png';
    }else{
        return loadImg($avantar, config('constants.avantarStorage').'thumbnail/');
    }
    
}

function generateLogMsg(Exception $exception) {
    $message = $exception->getMessage();
    $trace   = $exception->getTrace();

    $first_trace = head($trace);
    $file = data_get($first_trace, 'file');
    $line = data_get($first_trace, 'line');
    return $message . ' at '. $file . ':' . $line;
}

/**
 * Format price display
 *
 * @param mixed  $number        [string|int|float need format price]
 * @param string $symbol        [symbol after price]
 * @param bool   $insert_before [true => insert symbol before, else insert after price]
 *
 * @return string
 */
function formatPrice($number, $symbol = '', $insert_before = false)
{
    if (empty($number)) {
        return $insert_before == true ? $symbol.(int)$number : (int)$number.$symbol;
    }
    $number   = removeFormatPrice((string)$number);
    $parts    = explode(".", $number);
    $pattern  = '/\B(?=(\d{3})+(?!\d))/';
    $parts[0] = preg_replace($pattern, ".", $parts[0]);
    return $insert_before == true ? $symbol.implode(".", $parts) : implode(".", $parts).$symbol;
}
/**
 * Remove format price become string
 *
 * @param string $string [string for remove format price]
 *
 * @return string
 */
function removeFormatPrice($string) 
{
    if (empty($string)) {
        return $string;
    }
    $pattern = '/[^0-9|.]+/';
    $string  = preg_replace($pattern, "", $string);
    return $string;
}

/**
 * Remove format number of all element inside array
 *
 * @param array $price_list [array need remove format number price]
 * 
 * @return array
 */
function removeFormatPriceList(array $price_list)
{
    if (empty($price_list)) {
        return [];
    }

    $result = [];
    foreach ($price_list as $key => $value) {
        if (is_array($value)) {
            $result[$key] = removeFormatPriceList($value);
        } else {
            $result[$key] = removeFormatPrice($value);
        }
    }
    return $result;
}

function array_shift_assoc( &$arr ){
    $val = reset( $arr );
    unset( $arr[ key( $arr ) ] );
    return $val; 
}


function getVNLetterDate() {
    $letter =  Carbon\Carbon::now();
    $letter = $letter->addDays(2);
    if ($letter->isWeekday(6)) {
        $letter = $letter->addDays(2);
    } else if ($letter->isWeekday(0)) {
        $letter = $letter->addDays(1);
    }
    return $letter->toDateString();
}

function dateConvertToString($date = null) 
    { 
    try {
        $_s = strtotime(date("Y-m-d H:i:s")) - strtotime($date);
        if(round($_s / (60*60*24)) >= 1)
        {
            // to day
            $rs_date = round($_s / (60*60*24)) . " day ago";
        }
        else
        {
            if(round($_s / (60*60)) >= 1)
            {
                // to hours
                $rs_date = round($_s / (60*60)) . " hours ago";
            }
            else
            {
                // to minutes
                $rs_date = round($_s / 60) . " minutes ago";
            }
        }   
    } catch (\Exception $e) {
        $rs_date = null;
    }
    return $rs_date;
}

// return start , end hours from daterangepickker

function getHourStartEnd($text){
    //24/10/2014 00:00 - 30/10/2014 23:59
    
    $start = trim(explode('-', $text)[0]);
    $end = trim(explode('-', $text)[1]);

 
    return [
        'date_start' =>  explode(' ', $start)[0],
        'hours_start' =>  explode(' ', $start)[1],
        'date_end' =>  explode(' ', $end)[0],
        'hours_end' =>  explode(' ', $end)[1],
    ];
}


// print leter payment method

function payMethod($HBS_CL_CLAIM, $lang = null){
    $name_reciever = "";
    $info_reciever = "";
    $banking = "";
    $notify = "";
    //CL_PAYMENT_METHOD_TT
    switch ($HBS_CL_CLAIM->payMethod) {
        case 'CL_PAYMENT_METHOD_TT':
            $account_no_lang = $lang == "en" ? "Account No.: " : "S??? t??i kho???n: ";
            $name_reciever = $HBS_CL_CLAIM->member->cl_pay_acct_name;
            $info_reciever = $account_no_lang.$HBS_CL_CLAIM->member->cl_pay_acct_no;
            //$banking = $HBS_CL_CLAIM->member->bank_name.', '.$HBS_CL_CLAIM->member->cl_pay_bank_branch.', '. $HBS_CL_CLAIM->member->cl_pay_bank_city;
            $banking = (!($HBS_CL_CLAIM->member->cl_pay_bank_city) && !($HBS_CL_CLAIM->member->cl_pay_bank_branch)) ? $HBS_CL_CLAIM->member->BankNameChange : $HBS_CL_CLAIM->member->BankNameChange.', ' . 
            ($HBS_CL_CLAIM->member->cl_pay_bank_branch ? $HBS_CL_CLAIM->member->cl_pay_bank_branch .', ' : "") . 
            $HBS_CL_CLAIM->member->cl_pay_bank_city;
            $notify = $lang == "en" ? 
            "Within 03 days from the date of sending this notice, if we do not receive your response, we understand that you have agreed with this claim result and Pacific Cross Viet Nam will settle the payment within 03 following days or within 03 days from the date of receiving full legal invoices and documents (if these invoices, documents have not submitted).": 
                "Trong v??ng 03 ng??y k??? t??? ng??y g???i th?? th??ng b??o n??y, n???u ch??ng t??i kh??ng nh???n ???????c ph???n h???i c???a Qu?? kh??ch, ch??ng t??i hi???u r???ng Qu?? kh??ch ???? ?????ng ?? v???i k???t qu??? b???i th?????ng n??y v?? Pacific Cross Vi???t Nam s??? ti???n h??nh thanh to??n b???i th?????ng trong v??ng 03 ng??y ti???p theo ho???c trong v??ng 03 ng??y k??? t??? ng??y nh???n ???????c ?????y ????? h??a ????n, ch???ng t??? h???p l??? (tr?????ng h???p nh???ng h??a ????n, ch???ng t??? n??y ch??a ???????c b??? sung).";
            $not_show_table = false;
            break;
        case 'CL_PAYMENT_METHOD_CH':
            $name_reciever = $HBS_CL_CLAIM->member->cash_beneficiary_name;
            $passport_lang = $lang == "en" ? "ID CARD / PASSPORT: " : "CMND/CCCD: ";
            $date_supply_lang = $lang == "en" ? "date of supply: " : "ng??y c???p: ";
            $providers_lang = $lang == "en" ? "provider: " : 'n??i c???p:';

            $info_reciever = $passport_lang .$HBS_CL_CLAIM->member->cash_id_passport_no.', '.$date_supply_lang  
            .Carbon\Carbon::parse($HBS_CL_CLAIM->member->cash_id_passport_date_of_issue)->format('d/m/Y').',  '.$providers_lang. $HBS_CL_CLAIM->member->cash_id_passport_issue_place;
            //$banking = $HBS_CL_CLAIM->member->cash_bank_name.', '.$HBS_CL_CLAIM->member->cash_bank_branch.', '.$HBS_CL_CLAIM->member->cash_bank_city ;
            $banking = $HBS_CL_CLAIM->member->CashBankNameChange.', '.$HBS_CL_CLAIM->member->cash_bank_branch.', '.$HBS_CL_CLAIM->member->cash_bank_city ;
            $notify = $lang == "en" ? 
            "Within 03 days from the date of sending this notice, if we do not receive your response, we understand that you have agreed with this claim result and Pacific Cross Viet Nam will settle the payment within 03 following days or within 03 days from the date of receiving full legal invoices and documents (if these invoices, documents have not submitted).": 
            "Trong v??ng 03 ng??y k??? t??? ng??y g???i th?? th??ng b??o n??y, n???u ch??ng t??i kh??ng nh???n ???????c ph???n h???i c???a Qu?? kh??ch, ch??ng t??i hi???u r???ng Qu?? kh??ch ???? ?????ng ?? v???i k???t qu??? b???i th?????ng n??y v?? Pacific Cross Vi???t Nam s??? ti???n h??nh thanh to??n b???i th?????ng trong v??ng 03 ng??y ti???p theo ho???c trong v??ng 03 ng??y k??? t??? ng??y nh???n ???????c ?????y ????? h??a ????n, ch???ng t??? h???p l??? (tr?????ng h???p nh???ng h??a ????n, ch???ng t??? n??y ch??a ???????c b??? sung).";
            $not_show_table = false;
            break;
        case 'CL_PAYMENT_METHOD_CQ':
            $name_reciever = $HBS_CL_CLAIM->member->cash_beneficiary_name;
            $info_reciever = " ";
            $banking = "";
            $notify = $lang == "en" ? 
                "<p>Cash pick up at Pacific Cross Vietnam</p>
                <ul>
                    <li>16th Floor, Tower B, Royal Center Building, 235 Nguyen Van Cu, Nguyen Cu Trinh Ward, District 1, HCMC. HCM <br />Tel: (+84 28) 3821 9908</li>
                    <li>19th Floor, VCCI Tower, 9 Dao Duy Anh St., Dong Da Dist., Hanoi, Vietnam <br />Tel: (+84 24) 7308 6699</li>
                </ul>
                <p>(Please bring your ID card to the receiving office from Monday to Friday every week after 1 working day from the date of accepting payment)</p>":
                "<p>Nh???n ti???n m???t t???i Pacific Cross Vietnam</p>
                <ul>
                    <li>L???u 16, Th??p B, T??a nh?? Royal Centre, 235 Nguy???n V??n C???, Ph?????ng Nguy???n C?? Trinh, Qu???n 1, TP. HCM  <br /> ??T: (+84 28) 3821 9908</li>
                    <li>L???u 19, To?? Nh?? VCCI Tower, 9 ????o Duy Anh, Q. ?????ng ??a, H?? N???i <br /> ??T: (+84 24) 7308 6699</li>
                </ul>
                <p>(Qu?? kh??ch vui l??ng mang theo CMND ?????n V??n ph??ng nh???n ti???n t??? Th??? Hai ?????n Th??? S??u h??ng tu???n sau 1 ng??y l??m vi???c k??? t??? ng??y ch???p nh???n thanh to??n)</p>";
            $not_show_table = false;
            break;
        default:
            $name_reciever = " ";
            $info_reciever = " ";
            $banking = "";
            $notify = $lang == "en" ? " Pay the premium for contract ": (" ????ng ph?? b???o hi???m cho h???p ?????ng s??? ". $HBS_CL_CLAIM->Police->pocy_ref_no) ;
            $not_show_table = true;
            break;
    }
    $pay_lang = $lang == "en" ? 'Pacific Cross Viet Nam will pay the above amount in the following form:' : 'Pacific Cross Vi???t Nam s??? thanh to??n s??? ti???n tr??n theo h??nh th???c sau:';
    $beneficiary_name = $lang == "en" ? 'Beneficiary name: ' : 'T??n ng?????i th??? h?????ng: ';
    $info_bank = $lang == "en" ? 'Name and Address of the Bank: ' : 'T??n v?? ?????a ch??? Ng??n h??ng: ';

    $payMethod =    '<p><span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;">'.$pay_lang.'</span></p>
                    <table style=" width: 100%; border: 1px solid black; border-collapse: collapse;">
                        <tbody>
                        <tr>
                            <td style="border: 1px solid black; width: 350px; font-family: arial, helvetica, sans-serif ; font-size: 10pt">
                                <p>'.$beneficiary_name.$name_reciever.'</p>
                            </td>
                            <td style="border: 1px solid black; width: 350px; font-family: arial, helvetica, sans-serif ; font-size: 10pt">
                                <p>'.$info_reciever.'</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black; font-family: arial, helvetica, sans-serif ; font-size: 10pt" colspan="2">
                                <p>'.$info_bank.$banking.'</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black; font-family: arial, helvetica, sans-serif ; font-size: 10pt" colspan="2">
                                <p>'.$notify.'</p>
                            </td>
                        </tr>
                    </tbody>
                    </table>';
    if($not_show_table){
        $payMethod = '<span style=" font-family: arial, helvetica, sans-serif ; font-size: 10pt;">'.$pay_lang.'<strong>'.$notify.'</strong></span>';
    }
    
    return $payMethod;
}

// print letter IOPDiag

function IOPDiag($HBS_CL_CLAIM, $claim_id , $lang = null){
    $IOPDiag = [];
    $ClaimWordSheet = App\ClaimWordSheet::where('claim_id',$claim_id)->first();
    if (data_get($ClaimWordSheet, 'type_of_visit') != null || data_get($ClaimWordSheet, 'type_of_visit') != []) {
        foreach (data_get($ClaimWordSheet, 'type_of_visit') as $key => $value) {
            if($lang == null || $lang == 'vn'){
                $IOPDiag_f[] = '<span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;"> Ng??y ??i???u tr???: '.  data_get($value,'from') . ( data_get($value,'to') == null ? "": " ?????n " . data_get($value,'to') )."<br>".
                "Ch???n ??o??n: " . data_get($value,'diagnosis') ." <br>".
                'N??i ??i???u tr???: '.data_get($value,'prov_name')." <br></span>";
            }else{
                $IOPDiag_f[] = '<span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;"> Treatment period:' .data_get($value,'from') . ( data_get($value,'to') == null ? "": " to " . data_get($value,'to') )."<br>".
                "Diagnosis: " . data_get($value,'diagnosis') ." <br>".
                'Place of treatment: '.data_get($value,'prov_name')." <br></span>";
            }
        }
    }else{
        foreach ($HBS_CL_CLAIM->HBS_CL_LINE as $key => $value) {
            $from_date = Carbon\Carbon::parse($value->incur_date_from)->format('d/m/Y');
            $to_date = Carbon\Carbon::parse($value->incur_date_to)->format('d/m/Y');
            $IOPDiag[$key]['date'] = "$from_date - $to_date";
            $IOPDiag[$key]['diagnosis'] = ($value->RT_DIAGNOSIS->diag_desc_vn == null || $lang == 'en' )  ?  $value->RT_DIAGNOSIS->diag_desc : $value->RT_DIAGNOSIS->diag_desc_vn ;
            $IOPDiag[$key]['place'] = $value->prov_name;
        }
        $IOPDiag = collect( $IOPDiag)->groupBy('place');
    
        foreach ($IOPDiag as $key => $value) {
            if($lang == null || $lang == 'vn'){
                $IOPDiag_f[] = '<span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;"> Ng??y ??i???u tr???: '.$value->unique('date')->implode('date' , "; " )."<br>".
                "Ch???n ??o??n: " . $value->unique('diagnosis')->implode('diagnosis' , ", " ) ." <br>".
                'N??i ??i???u tr???: '.$value[0]['place']." <br></span>";
            }else{
                $IOPDiag_f[] = '<span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;"> Treatment period:' .$value->unique('date')->implode('date' , "; " )."<br>".
                "Diagnosis: " . $value->unique('diagnosis')->implode('diagnosis' , ", " ) ." <br>".
                'Place of treatment: '.$value[0]['place']." <br></span>";
            }
        }
    }
    $IOPDiag = implode('<br>',  $IOPDiag_f);
    return $IOPDiag;
}

function IOPDiagWookSheet($HBS_CL_CLAIM){
    $IOPDiag = [];
        foreach ($HBS_CL_CLAIM->HBS_CL_LINE as $key => $value) {
            switch ($value->PD_BEN_HEAD->scma_oid_ben_type) {
                case 'BENEFIT_TYPE_OP':
                    $IOPDiag[$key]['from'] = Carbon\Carbon::parse($value->incur_date_from)->format('d/m/Y');
                    $IOPDiag[$key]['to'] = null;
                    $IOPDiag[$key]['diagnosis'] = $value->RT_DIAGNOSIS->diag_desc_vn;
                    $IOPDiag[$key]['prov_name'] = $value->prov_name;
                    break;
                case 'BENEFIT_TYPE_IP':
                    $IOPDiag[$key]['from'] = Carbon\Carbon::parse($value->incur_date_from)->format('d/m/Y');
                    $IOPDiag[$key]['to'] = Carbon\Carbon::parse($value->incur_date_to)->format('d/m/Y');
                    $IOPDiag[$key]['diagnosis'] = $value->RT_DIAGNOSIS->diag_desc_vn;
                    $IOPDiag[$key]['prov_name'] = $value->prov_name;
                    break;
                default:

                    break;
            }
        }
    
    return $IOPDiag;
}
// print letter benefitOfClaim

function benefitOfClaim($HBS_CL_CLAIM){
   
    $benefitOfClaim = [];
        foreach ($HBS_CL_CLAIM->HBS_CL_LINE as $key => $value) {
            switch ($value->PD_BEN_HEAD->scma_oid_ben_type) {
                case 'BENEFIT_TYPE_DT':
                    $benefitOfClaim[] = 'ch??m s??c r??ng';
                    break;
                case 'BENEFIT_TYPE_OP':
                    $benefitOfClaim[] = 'ngo???i tr??';
                    break;
                default:
                    $benefitOfClaim[] = 'n???i tr??';
                    break;
            }
        }
    $benefitOfClaim = implode(', ', array_unique($benefitOfClaim));
    return $benefitOfClaim;
}


// print CRRMArk AND TERM

function CSRRemark_TermRemark($claim ,$lang = null){
    $CSRRemark = [];
    $TermRemark = [];
    $hasTerm3 = null;
    $hasTerm2 = null;
    $hasTerm1 = null;
    
    $arrKeyRep = [ '[##nameItem##]' , '[##amountItem##]' , '[##Date##]' , '[##Text##]' ];
    $item = ItemOfClaim::where('claim_id', $claim->id)->where('status',1)->get();
    $itemsReject = $item->pluck('content')->toArray();
    $amountsReject = $item->pluck('amount');
    $sumAmountReject = 0;
    foreach ($amountsReject as $key => $value) {
        $sumAmountReject += removeFormatPrice($value);
    }
    $itemOfClaim = $claim->item_of_claim->groupBy('reason_reject_id');
    $templateHaveMeger = [];
    foreach ($itemOfClaim as $key => $value) {
        $template = $lang == 'en' ?  "- ".$value[0]->reason_reject->template_en : "- ".$value[0]->reason_reject->template;
        
        if(isset($value[0]->reason_reject->term)){
            foreach ($value[0]->reason_reject->term as $key_t => $value_t) {
                $TermRemark[] = $lang == 'en' ? $value_t->fullTextTermEn : $value_t->fullTextTerm;
            }
        }
        if (!preg_match('/\[Begin\].*\[End\]/U', $template)){
            foreach ($value as $keyItem => $item) {
                $template_new = $template;
                foreach ( $arrKeyRep as $key2 => $value2) {
                    $template_new = str_replace($value2, '$parameter', $template_new);
                };
                $CSRRemark[] =  str_replace("  "," ",trim(Str::replaceArray('$parameter', $item->parameters, $template_new)));
            }
        }else{
            preg_match_all('/\[Begin\].*\[End\]/U', $template, $matches);
            $template_new = preg_replace('/\[Begin\].*\[End\]/U' , '$arrParameter' , $template );
            $arrMatche = [];
            foreach ($value as $keyItem => $item) {
                foreach ($matches[0] as $keyMatche => $valueMatche) {
                    foreach ( $arrKeyRep as $key2 => $value2) {
                        $valueMatche = str_replace( $value2, '$parameter', $valueMatche);
                    };
                    $arrMatche[$keyMatche][] =  Str::replaceArray('$parameter',$item->parameters, $valueMatche);
                }
            }
            // array to string 
            $arr_str = [];
            foreach ($arrMatche as $key => $value) {
                
                $arr_str[] = preg_replace('/\[Begin\]|\[End\]/', '', implode(", ", $value));
            }
            $CSRRemark[] =  str_replace("  "," ",trim(Str::replaceArray('$arrParameter', $arr_str, $template_new)));
        }
    }
    
    $TermRemark = collect($TermRemark)->unique()->toArray();
    return [ 'CSRRemark' => $CSRRemark , 'TermRemark' => $TermRemark , 'itemsReject' => $itemsReject , 'sumAmountReject' => $sumAmountReject];
    
}

function note_pay($export_letter){
    $htm = '<p style = "font-size: 10px; padding: 0px ;margin: 0px">Note: : Claim s??? [[$claimNo]] t???ng thanh to??n b???i th?????ng [[$apvAmt]] ?????ng.</p>';
    if(!empty($export_letter->data_cps) || $export_letter->data_cps != null){
        foreach ($export_letter->data_cps as $key => $value) {
            $tf_date =  Carbon\Carbon::parse($value['TF_DATE'])->format('d/m/Y');
            $tf_amt = formatPrice($value['TF_AMT']);
            if($value['TF_DATE'] != null){
                $htm .= "<p style='font-size: 10px; padding: 0px ;margin: 0px'>Payment l???n {$value['PAYMENT_TIME']} ng??y {$tf_date} thanh to??n cho kh??ch h??ng {$tf_amt} ?????ng.</p>";
            }else{
                $htm .= "<p style='font-size: 10px; padding: 0px ;margin: 0px'>Payment l???n {$value['PAYMENT_TIME']}  thanh to??n cho kh??ch h??ng {$tf_amt} ?????ng.</p>";
            }
        }
    }
    if(data_get($export_letter->info,'PCV_EXPENSE', 0) != 0){
        $htm .= "<p style='font-size: 10px; padding: 0px ;margin: 0px'>TT d?? " . formatPrice(data_get($export_letter->info,'PCV_EXPENSE', 0))." ?????ng.</p>";
    }
    return $htm;
}

function datepayment(){
    $now = Carbon\Carbon::now();
    return $now->format('d/m/Y');
}
function notifi_system($content, $arrUserID = []){
    $user = App\User::findOrFail(1);
    $data['title'] = $user->name . ' g???i tin cho b???n';
    $data['content'] = $content;
    $data['avantar'] = config('constants.avantarStorage').'thumbnail/'.$user->avantar;
    $data_messageSent = [];
    foreach ($arrUserID as $key => $value) {
        $data_messageSent[] = [
            'user_to' => $value,
            'message' => $content
        ];
    }
    $mesage_data = $user->messagesSent()->createMany($data_messageSent);
    $user_to = User::whereIn('id', $arrUserID)->whereNotNull('device_token')->pluck('device_token');
    $SERVER_API_KEY = config('constants.SERVER_API_KEY');
    $data = [
        "registration_ids" => $user_to,
        "notification" => [
            "title" => $data['title'],
            "body" => strip_tags(html_entity_decode($data['content'])), 
            "icon"=> asset("images/logo.png"),
            
        ],
        "webpush"=> [
            "fcm_options" => [
                "link" => url('admin/message')
            ]
        ]
    ];
    $dataString = json_encode($data);
    $headers = [
        'Authorization: key=' . $SERVER_API_KEY,
        'Content-Type: application/json',
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
    $response = curl_exec($ch);
    
    return redirect('/admin/home/');
}


// Get token CPS
function getTokenCPS(){
    $headers = [
        'Content-Type' => 'application/json',
    ];
    $body = [
        'client_id' => config('constants.client_id'),
        'client_secret' => config('constants.client_secret'),
        'grant_type' => config('constants.grant_type'),
    ];
    $setting = Setting::where('id', 1)->first();
    if($setting === null){
        $setting = Setting::create([]);
    }
    $startTime = Carbon\Carbon::parse($setting->updated_token_cps_at);
    $now = Carbon\Carbon::now();
    $totalDuration = $startTime->diffInSeconds($now);
    if($setting->token_cps == null || $totalDuration >= 3500 || $setting->updated_token_cps_at == null){
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();
            $client = new \GuzzleHttp\Client([
                'headers' => $headers
            ]);
            $response = $client->request("POST", config('constants.api_cps').'get_token' , ['form_params'=>$body]);
            $response =  json_decode($response->getBody()->getContents());
            $token = data_get($response , 'access_token',null);
            if($token){
                $setting->token_cps = $token;
                $setting->updated_token_cps_at = $now;
                $setting->save();
                \Illuminate\Support\Facades\DB::commit();
            }else{
                \Illuminate\Support\Facades\DB::rollback();
            }
        } catch (Exception $e) {
            \Illuminate\Support\Facades\DB::rollback();
        }
    }
    return  $setting->token_cps;
}

function typeGop($value){
    $rp = "";
    foreach (config('constants.gop_type') as $key_type => $value_type) {
        $checked = $value == $key_type ? 'checked' : '';
        $rp .=   "<input type='radio' {$checked}>
                <span style='font-family: serif; font-size: 10pt;'>{$value_type}</span><br>";
    }
    return $rp;
}

function numberToRomanRepresentation($string) {
    $chars = preg_split('//', $string, -1, PREG_SPLIT_NO_EMPTY);
    $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
    $returnValue = '';
    foreach ($chars as $key => $number) {
        if(is_numeric($number) && $number != 0){
            while ($number > 0) {
                foreach ($map as $roman => $int) {
                    if($number >= $int) {
                        $number -= $int;
                        $returnValue .= $roman;
                        break;
                    }
                }
            }
        }else{

            $returnValue .= $number =='0' ? "O" : $number;
        }
        
    }
    
    return $returnValue;
}

function formatVN($string)
{
    $pattern  = '/[^a-z0-9A-Z_[:space:]?????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????? ??????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????]/u';
    return preg_replace($pattern, "", $string);;
}

function vn_to_str($str){
 
    $unicode = array(
    
    'a'=>'??|??|???|??|???|??|???|???|???|???|???|??|???|???|???|???|???',
    
    'd'=>'??',
    
    'e'=>'??|??|???|???|???|??|???|???|???|???|???',
    
    'i'=>'??|??|???|??|???',
    
    'o'=>'??|??|???|??|???|??|???|???|???|???|???|??|???|???|???|???|???',
    
    'u'=>'??|??|???|??|???|??|???|???|???|???|???',
    
    'y'=>'??|???|???|???|???',
    
    'A'=>'??|??|???|??|???|??|???|???|???|???|???|??|???|???|???|???|???',
    
    'D'=>'??',
    
    'E'=>'??|??|???|???|???|??|???|???|???|???|???',
    
    'I'=>'??|??|???|??|???',
    
    'O'=>'??|??|???|??|???|??|???|???|???|???|???|??|???|???|???|???|???',
    
    'U'=>'??|??|???|??|???|??|???|???|???|???|???',
    
    'Y'=>'??|???|???|???|???',
    
    );
    
    foreach($unicode as $nonUnicode=>$uni){
    
    $str = preg_replace("/($uni)/i", $nonUnicode, $str);
    
    }
    
    return trim($str);
    
}

function getTokenMfile(){
    $headers = [
        'Content-Type' => 'application/json',
    ];
    $body = [
        'email' => config('constants.account_mfile'),
        'password' => config('constants.pass_mfile')
    ];
    $setting = Setting::where('id', 1)->first();
    if($setting === null){
        $setting = Setting::create([]);
    }
    
    $startTime = Carbon\Carbon::parse($setting->updated_token_mfile_at);
    $now = Carbon\Carbon::now();
    $totalDuration = $startTime->diffInSeconds($now);
    
    if($setting->token_mfile == null || $totalDuration >= 3500){
        $client = new \GuzzleHttp\Client([
            'headers' => $headers
        ]);
        
        $response = $client->request("POST", config('constants.link_mfile').'login' , ['form_params'=>$body]);
        $response =  json_decode($response->getBody()->getContents());
        Setting::where('id', 1)->update([
            'token_mfile' => data_get($response , 'token'),
            'updated_token_mfile_at' => Carbon\Carbon::now()->toDateTimeString()
        ]);
        
    }
    return  $setting->token_mfile;
}

function send_message_mobile($title,$message,$claim, $file_base64 = null){
    $headers = [
        'Content-Type' => 'application/json',
    ];
    $body = [
        'title' => $title,
        'message' => $message,
        'mantis_id' => $claim->barcode,
        'company' => config('constants.company'),
        'file'    => $file_base64
    ];
    $client = new \GuzzleHttp\Client([
        'headers' => $headers
    ]);
    try {
        $client = new \GuzzleHttp\Client([
            'headers' => $headers
        ]);
        $response = $client->request("POST", config('constants.url_mobile_api').'claim/send-message' , ['form_params'=>$body]);
        $response =  json_decode($response->getBody()->getContents());
        return $response;
    }catch (GuzzleHttp\Exception\ClientException $e) {
        $response = $e->getResponse()->getBody(true);
        $response = json_decode((string)$response);
        return $response;
    }
    
}

function renderMessageInvoice($id){
    $claim = \App\Claim::findOrFail($id);
    $str = "H??? s?? y??u c???u b???i th?????ng c???a b???n ???? ???????c x??t duy??t.";
    if(	$claim->original_invoice_type == 'No' && $claim->original_invoice_no_not_ready != null){
        $str .= "\n + Vui l??ng g???i h??a ????n VAT b???n g???c s??? '$claim->original_invoice_no_not_ready' v??? ?????a ch???  L???u 16, T??a nh?? Royal -  th??p B,  235 Nguy???n V??n C???, Qu???n  1, HCM, Vi???t Nam.";
    }
    if(	$claim->e_invoice_type == 'No' && $claim->e_invoice_no_not_ready != null){
        $str .= "\n + Vui l??ng cung c???p th??m th??ng tin h??a ????n VAT s??? '$claim->e_invoice_no_not_ready' .";
    }
    if(	$claim->converted_invoice_type == 'No' && $claim->converted_invoice_no_not_ready != null){
        $str .= "\n + Vui l??ng g???i h??a ????n VAT ???? chuy???n ?????i b???n g???c s??? '$claim->original_invoice_no_not_ready' v??? ?????a ch???  L???u 16, T??a nh?? Royal -  th??p B,  235 Nguy???n V??n C???, Qu???n  1, HCM, Vi???t Nam.";
    }
    return $str;
}

function getTokenSms(){
    $headers = [
        'Content-Type' => 'application/json',
    ];
    $body = [
        'client_id' => config('constants.client_id_sms'),
        'client_secret' => config('constants.client_secret_sms'),
        'grant_type' => config('constants.grant_type'),
        'scope' => 'send_brandname_otp',
        'session_id' => '789dC48b88e54f58ece5939f14a'
    ];
    $setting = Setting::where('id', 1)->first();
    if($setting === null){
        $setting = Setting::create([]);
    }
    $startTime = Carbon\Carbon::parse($setting->created_token_sms_at);
    $now = Carbon\Carbon::now();
    $totalDuration = $startTime->diffInSeconds($now);
    if($setting->created_token_sms_at == null || $setting->token_sms == null || $totalDuration >= 3500){
        $client = new \GuzzleHttp\Client([
            'headers' => $headers
        ]);
        $response = $client->request("POST", config('constants.api_sms').'oauth2/token' , ['form_params'=>$body]);
        $response =  json_decode($response->getBody()->getContents());
        $setting->token_sms = data_get($response , 'access_token');
        $setting->created_token_sms_at = $now->toDateTimeString();
        $setting->save();
    }
    return  $setting->token_sms;
}

function sendSms($phone,$sms){
    $token = getTokenSms();
    $pattern = '/[^0-9]+/';
    $num  = preg_replace($pattern, "", $phone);
    $headers = [
        'Content-Type' => 'application/json',
    ];
    $body = [
        'access_token' => $token,
        'session_id' => '789dC48b88e54f58ece5939f14a',
        'BrandName' => 'PACIFICROSS',
        'scope' => 'send_brandname_otp',
        'Phone' => $num,
        'Message' => base64_encode($sms)
    ];

    try {
        $client = new \GuzzleHttp\Client([
            'headers' => $headers
        ]);
        $response = $client->request("POST", config('constants.api_sms').'api/push-brandname-otp' , ['form_params'=>$body]);
        $response =  json_decode($response->getBody()->getContents());
        return $response;
    }catch (GuzzleHttp\Exception\ClientException $e) {
        $response = $e->getResponse()->getBody(true);
        $response = json_decode((string)$response);
        return $response;
    }
    
}
