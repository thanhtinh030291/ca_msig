<!-- Stored in resources/views/layouts/admin/partials/top_bar_navigation.blade.php -->
@php
$sum = 0;
$totalAmount = 0;
@endphp
@extends('layouts.admin.master')
@section('title', __('message.claim_view'))
@section('stylesheets')
    <link href="{{asset('css/fileinput.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
    <link href="{{asset('css/formclaim.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
    <link href="{{asset('css/ckeditor.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
    <link href="{{asset('plugins/datatables/dataTables.bootstrap4.min.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
    <link href="{{asset('css/jquery.tag-editor.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
    <style>
        .disableRow {
            background-color: lightslategrey;
        }
        table.dataTable thead>tr>th {
            font-size: 11px !important;
        }
        .modal-lg {
            max-width: 1200px !important;
        }
    </style>
@endsection
@section('content')
@include('layouts.admin.breadcrumb_index', [
    'title'       => __('message.claim_create'),
    'parent_url'  => route('claim.index'),
    'parent_name' => __('message.claim_management'),
    'page_name'   => __('message.claim_view'),
])
<div class="row">
    <div class="col-md-12">
        <div class="card"> 
            <div class="card-body">
            
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body row">
                                <div class="col-md-7">
                                    
                                    <h5 class="card-title">Request Letter</h5>
                                    <p class="card-text"></p>
                                    {{ Form::open(array('url' => '/admin/requestLetter', 'method' => 'POST')) }}
                                        {{ Form::hidden('claim_id', $data->id ) }}
                                        {{ Form::label('letter_template_id', __('message.letter_template'), array('class' => 'labelas')) }}
                                        <div class="row">
                                            <div class="col-md-8 pr-0">
                                                {{ Form::select('letter_template_id', $listLetterTemplate, old('letter_template_id'), array('id'=>'letterTemplate', 'class' => 'select2 form-control', 'required')) }}
                                            </div>
                                            <div class="col-md-4 p-0">
                                                {!! Form::button('Send Letter', ['data-toggle' => "modal" ,  
                                                'data-target' => "#comfirmPaymentModal",
                                                'type' => 'button', 
                                                'class' => ' btn btn-info' , 
                                                'onclick' => 'comfirmPayment(this);',
                                                ]) !!}
                                            </div>
                                        </div>
                                    {{ Form::close() }}
                                    {{-- claimWordSheets --}}
                                    {{ Form::label('claimWordSheets', 'Claim Word Sheet', array('class' => 'labelas')) }}
                                    @if($data->claim_word_sheet)
                                    <a target="_blank" href="{{route('claimWordSheets.show', ['claimWordSheet' => $data->claim_word_sheet->id])}}" class="mt-3 btn btn-info">Link Work Sheet</a><br>
                                    @else
                                        {{ Form::open(array('url' => route('claimWordSheets.store'))) }}
                                        {{ Form::hidden('claim_id', $data->id ) }}
                                        {{ Form::hidden('mem_ref_no', $data->clClaim->member->mbr_no ) }}
                                        <button class="btn btn-info" type="submit" value="save">Run</button> 
                                        {{ Form::close() }}
                                    @endif

                                    {{ Form::label('CSR_File', 'CSR File ', array('class' => 'labelas')) }}<br>
                                    {!! Form::button('CSR File', ['data-toggle' => "modal" ,  'data-target' => "#csrModal", 'type' => 'button', 'class' => ' btn btn-info' ]) !!}<br>

                                    {{-- payment request  --}}
                                    {{ Form::label('Payment_Request', 'Payment Request', array('class' => 'labelas')) }}
                                    
                                    @if($can_pay_rq == true)
                                            {!! Form::button('Yêu Cầu Finance Thanh Toán', ['data-toggle' => "modal" ,  
                                                'data-target' => "#requetPaymentModal",
                                                'type' => 'button', 
                                                'class' => ' btn btn-info' , 
                                                
                                                ]) !!}
                                    @endif
                                    <div class="card mt-2">
                                        {{-- invoiceModal--}}
                                        @include('claimManagement.invoiceModal')
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    {{ Form::open(array('url' => '/admin/claim/uploadSortedFile/'.$data->id, 'method'=>'post', 'files' => true))}}
                                    <h5 class="card-title">Tệp đã được sắp sếp</h5>
                                    <div class="file-loading">
                                        <input id="url_file_sorted" type="file" name="_url_file_sorted[]" >
                                    </div>
                                    <div class="mt-2" >
                                        <button type="submit" class="btn btn-primary " > {{__('message.save')}}</button>
                                        <button type="button" onclick="upload_summary()" class="btn btn-primary m-2">Send to summary Etalk</button>
                                        {!! Form::button('Delete pages', ['data-toggle' => "modal" ,  
                                                'data-target' => "#deletePagesModal",
                                                'type' => 'button', 
                                                'class' => ' btn text-danger' , 
                                                'onclick' => 'comfirmPayment(this);',
                                                ]) !!}
                                    </div>
                                    <!-- End file image -->
                                    {{ Form::close() }}
                                    {{ Form::label('inv_nos', 'Số hóa đơn (HBS)', array('class' => 'labelas')) }}
                                    {{ Form::text('inv_nos', $inv_nos, [ 'class' => 'tag-editor form-control','placeholder' =>'mã hóa đơn']) }}<br/>
                                    
                                    {{-- <button type="button" onclick="sendMfile();" class="btn btn-success " ><i class="fa fa-mixcloud" aria-hidden="true"></i> Send To M-Files</button>
                                    @if ($data->LogMfile != null)
                                        <p id="mfile-update-time">M-File latest version at {{$data->LogMfile->updated_at}}
                                            <a target="_blank" href="/admin/viewMfile/{{$data->LogMfile->m_claim_id}}/{{$data->LogMfile->m_claim_file_id}}" ><i class="fa fa-eye"></i></a>
                                    @else
                                        <p id="mfile-update-time"></p>
                                    @endif --}}
                                </div> 
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            {{ Form::label('type',  'Member Name', array('class' => 'col-md-4')) }}
                            {{ Form::label('type', $data->member_name , array('class' => 'col-md-8')) }}

                            {{ Form::label('type',  __('message.id_claim'), array('class' => 'col-md-4')) }}
                            {{ Form::label('type', $data->code_claim , array('class' => 'col-md-8')) }}

                            {{ Form::label('type',  'Claim Code', array('class' => 'col-md-4')) }}
                            {{ Form::label('type', $data->code_claim_show , array('class' => 'col-md-8')) }}

                            {{ Form::label('type',  'Barcode', array('class' => 'col-md-4')) }}
                            {{ Form::label('type', $data->barcode , array('class' => 'col-md-8')) }}

                            {{ Form::label('type',  'Etalk Link', array('class' => 'col-md-4')) }}
                            <a class="btn btn-primary col-md-8 " target="_blank" href="{{config('constants.url_mantic').'view.php?id='.$data->barcode }}">Link</a>
                            
                            
                            {{ Form::label('type',  __('message.account_create'), array('class' => 'col-md-4')) }}
                            {{ Form::label('type', $admin_list[$data->updated_user] ." ". $data->created_at, array('class' => 'col-md-8')) }} 

                            {{ Form::label('type',  __('message.account_edit'), array('class' => 'col-md-4')) }} 
                            {{ Form::label('type', $admin_list[$data->updated_user] ." ". $data->updated_at, array('class' => 'col-md-8')) }}

                            {{ Form::label('type',  "Approve Amt HBS", array('class' => 'col-md-4 ')) }}
                            {{ Form::label('type', formatPrice($approve_amt, " đ"), array( "id" => "apv_hbs_show", 'class' => 'col-md-8 text-danger font-weight-bold')) }}
                            {{-- Cấn trừ --}}
                            {{ Form::label('type',  "Payment History", array('class' => 'col-md-6 ')) }}
                            <div id="payment_history_show" class="col-md-12 border border-danger p-3 mb-3">
                                @foreach ($payment_history as $key => $value )
                                <p>Lần {{data_get($value, 'PAYMENT_TIME')}} : ({!!data_get($value, 'TF_DATE') ? '<span class="text-success font-weight-bold">Đã thanh toán </span>' . data_get($value, 'TF_DATE') : '<span class="text-info font-weight-bold">Đang Chờ Thanh Toán</span>'!!})
                                    <span class="text-danger font-weight-bold">{{formatPrice(data_get($value, 'TF_AMT'), " đ")}}</span>
                                    {{-- <button class="btn btn-primary p-1" data-toggle="collapse" data-target="#collapse{{data_get($value, 'PAYM_ID')}}" aria-expanded="true" aria-controls="collapseOne">
                                        <i class="fa fa-share-square-o" aria-hidden="true"></i> CPS
                                    </button> --}}
                                    <div id="collapse{{data_get($value, 'PAYM_ID')}}" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        
                                            {{ Form::open(array('url' => '/admin/claim/setPcvExpense/'.$data->id, 'method'=>'post', 'files' => true, 'style' => 'width: 100%;'))}}
                                            <div class="row">
                                                {{ Form::label('PCV_EXPENSE',  'PCV EXPENSE' , array('class' => 'col-md-4' , 'font-size' => '11px')) }}
                                                {{ Form::hidden('paym_id',  data_get($value, 'PAYM_ID') ) }}
                                                {{ Form::text('pcv_expense', null , array('id' => 'PCV_EXPENSE_SHOW','class' => 'col-md-5 item-price form-control'  )) }}
                                                <button class="btn btn-primary col-md-2">save</button>
                                            </div>
                                            {{ Form::close() }}
                                        
                                    </div>
                                </p>
                                @endforeach
                            </div>
                            {{--Dư nợ của user--}}
                            {{ Form::label('type',  "Member's Debt Balance", array('class' => 'col-md-6 ')) }}
                            {!! Form::button('View', ['data-toggle' => "modal" ,  
                                'data-target' => "#debtBalanceModal",
                                'type' => 'button', 
                                'class' => ' btn btn-info' ,
                            ]) !!}
                            @if($balance_cps->sum('DEBT_BALANCE') > 0)
                                <p class="text-danger">Khách Hàng đang nợ có thể đòi : <span class="font-weight-bold">{{formatPrice($balance_cps->sum('DEBT_BALANCE'), ' đ')}}</span></p>
                            @endif
                            {{--Customer confirm--}}
                            {{ Form::label('type',  "Customer confirm", array('class' => 'col-md-6 mt-2')) }}
                            {!! Form::button('Customer confirm', ['data-toggle' => "modal" ,  
                                                'data-target' => "#custommerConfirmModal",
                                                'type' => 'button', 
                                                'class' => ' btn btn-info mt-2' , 
                                                ]) !!}
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    @if($IS_FREEZED == 0)
                    <button type="button" class="btn btn-success btn-circle btn-lg"><i style="font-size: 24px" class="fa fa-unlock text-warning font-weight-bolder" aria-hidden="true"></i></button>
                    @else
                    <button type="button" data-toggle="modal" data-target="#unfreezedModal"  class="btn btn-danger btn-circle btn-lg"><i style="font-size: 24px" class="fa fa-lock text-warning font-weight-bolder" aria-hidden="true"></i></button>
                    @endif
                    <button type="button" data-toggle="modal" data-target="#closeClaimModal"  class="btn btn-danger btn-circle btn-lg"><i style="font-size: 24px" class="fa fa-window-close-o text-warning font-weight-bolder" aria-hidden="true"></i>Close Claim</button>
                    <button type="button" data-toggle="modal" data-target="#updateStatusEtalkModal"  class="btn btn-info btn-circle btn-lg"><i style="font-size: 24px" class="fa fa-stack-overflow font-weight-bolder" aria-hidden="true"></i>Update Status Etalk</button>
                </div>
                <!-- End file image -->
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                    <label  class="font-weight-bold" for="letter">{{__('message.letter')}}</label>
            </div> 
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name Letter</th>
                            <th>Info</th>
                            <th>Create By</th>
                            <th>Current Status</th>
                            <th>History</th>
                            <th>Note</th>
                            <th>Wait for the check</th>
                            
                            <th class='text-center'>{{ __('message.control')}}</th>
                        </tr>
                    </thead>
                    
                    @foreach ($data->export_letter as $item)
                        <tr class = "{{isset($item->info['note']) ? "disableRow" : ""}} " >
                            <td>{{$item->id}}</td>
                            <td>
                                {{$item->letter_template->name}}
                                @if( ($item->status == $item->end_status || ($item->status == 0 && $item->end_status == 13 ))  && !isset($item->info['note']) && $item->created_user == $user->id )
                                {{ Form::open(array('url' => '/admin/sendEtalk', 'method' => 'POST', 'class' => 'form-inline')) }}
                                    <div>
                                        {{ Form::hidden('id', $item->id) }}
                                        {{ Form::hidden('barcode', $data->barcode) }}
                                        {{ Form::hidden('claim_id', $item->claim_id) }}
                                        
                                        {!! Form::button('Send Etalk', 
                                        [   'type' => 'submit', 
                                            'class' => 'btn btn-info btn-md' 
                                        ]) !!}
                                    </div>
                                {!! Form::close() !!}
                                @endif
                                @if( ($item->status == $item->end_status || ($item->status == 0 && $item->end_status == 13 )) )
                                {!! Form::button('<i class="fa fa-paper-plane-o"></i>Sent Custommer', ['data-toggle' => "modal" ,  
                                        'data-target' => "#sendMailCustomerModal",
                                        'data-backdrop' => "static" ,
                                        'data-keyboard' => "false", 
                                        'type' => 'button', 
                                        'class' => 'btn btn-success btn-xs' , 
                                        'onclick' => 'sendMailCustomerModal(this);',
                                        'data-claim_id' => $data->id,
                                        'data-letter_template_id' => $item->letter_template->id,
                                        'data-status' => $item->status,
                                        'data-id' => $item->id
                                        ]) 
                                !!}
                                @endif

                                @if(isset($item->info['notes']))
                                    <h6> Added on Etalk</h6>
                                    <span>Note Id : {{data_get($item->info, "notes.id")}}</span><br>
                                    <a class="btn btn-success btn-xs" href="{{config("constants.url_cps").'mantis/'.$data->barcode}}" target="_blank">Link CPS</a>
                                    {{ Form::open(array('url' => '/admin/changeStatusEtalk', 'method' => 'POST', 'class' => 'form-inline')) }}
                                        <div>
                                            {{ Form::hidden('id', $item->id) }}
                                            {{ Form::hidden('barcode', $data->barcode) }}
                                            {{ Form::hidden('claim_id', $item->claim_id) }}
                                            {{ Form::hidden('message', 'Cập nhật lại status') }}
                                            {!! Form::button('Update Status Etalk', 
                                            [   'type' => 'submit', 
                                                'class' => 'btn btn-info btn-md' 
                                            ]) !!}
                                        </div>
                                    {!! Form::close() !!}
                                @endif
                            </td>
                            <td>
                                <p  class = "m-0">Approve AMT : </p> 
                                <span class="p-1 mb-2 bg-danger text-white   align-middle">{{formatPrice(data_get($item->info,'approve_amt'), ' đ')}} </span>
                                <p class = "m-0">End Status :  </p> 
                                <span class="p-1 mb-2 bg-info text-white  align-middle">{{data_get($list_status_ad, $item->end_status)}}</span>
                            </td>
                            <td>
                                <h6>{{$item->userCreated->name}}</h6>
                                <span>{{ $item->created_at }}</span>
                            </td>
                            <td>
                                <h5 class="p-0 text-primary">{{ data_get($list_status_ad,  $item->status ,'New') }} </h5>
                            </td>
                            <td>
                                {{-- history --}}
                                @include('history',[
                                    'idmodal' => 'items_'.$item->id,
                                    'log_history' => $item->log
                                ])
                            </td>
                            <td>
                                <button href="#collapse{{$item->id}}" class="nav-toggle btn btn-info pull-right p-1">▲</button>
                                @for ($i = count($item->note)-1; $i >= 0 ; $i--)
                                    @if($i == count($item->note)-2)
                                    <div id="collapse{{$item->id}}" style="display:none">
                                    @endif
                                    <div class="form-group">
                                        {!! Form::button('<i class="fa fa-commenting"></i>' . $admin_list[$item->note[$i]['user']], ['data-toggle' => "modal" ,  
                                        'data-target' => "#viewFileModal",
                                        'type' => 'button', 
                                        'class' => 'btn btn-danger btn-xs' , 
                                        'onclick' => 'viewFile(this);',
                                        'data-claim_id' => $data->id,
                                        'data-note' => $item->note[$i]['data'],
                                        'data-status' => $item->status,
                                        'data-id' => $item->id
                                        ]) !!}
                                        <br>
                                        {{$item->note[$i]['created_at']}}
                                    </div>
                                    @if($i == 0)
                                    </div>
                                    @endif
                                @endfor
                            </td>
                            <td>
                                @if (isset($item->wait['data']))                                
                                        {!! Form::button('<i class="fa fa-commenting"></i>' . $admin_list[$item->wait['user']], ['data-toggle' => "modal" ,  
                                        'data-target' => "#noteOrEditModal",
                                        "data-backdrop" => "static" ,
                                        "data-keyboard" => "false" ,
                                        'type' => 'button', 
                                        'class' => 'btn btn-success btn-xs' , 
                                        'onclick' => 'noteOrEdit(this);',
                                        'data-claim_id' => $data->id,
                                        'data-note' => $item->wait['data'],
                                        'data-status' => $item->created_user == $user->id ? config('constants.statusExport.edit') : config('constants.statusExport.note_save'),
                                        'data-id' => $item->id,
                                        'data-liststatus' => json_encode($item->list_status)
                                        ]) !!}
                                        <br>
                                        {{$item->wait['created_at']}}
                                        @if($item->created_user == $user->id)
                                            {{ Form::open(array('url' => '/admin/claim/sendSortedFile/'.$data->id, 'method'=>'post', 'files' => true))}}
                                                {{ Form::hidden('export_letter_id', $item->id ) }}
                                                {{ Form::hidden('letter_template_id', $item->letter_template->id ) }}
                                                {!! Form::button('<i class="fa fa-repeat"></i> Lưu vào tệp đã sắp sếp', ['type' => 'submit', 'class' => 'btn btn-info btn-xs p-1']) !!}
                                            {!! Form::close() !!}
                                            {{ Form::open(array('url' => '/admin/claim/downloadFinishFile/'.$data->id, 'method'=>'post', 'files' => true))}}
                                                {{ Form::hidden('export_letter_id', $item->id ) }}
                                                {{ Form::hidden('letter_template_id', $item->letter_template->id ) }}
                                                {!! Form::button('<i class="fa fa-repeat"></i> Download attach for CS', ['type' => 'submit', 'class' => 'btn btn-info btn-xs p-1']) !!}
                                            {!! Form::close() !!}
                                        @endif
                                @endif
                            </td>
                            <td>
                                {{ Form::open(array('url' => '/admin/exportLetter', 'method' => 'POST')) }}
                                @csrf
                                    {{ Form::hidden('claim_id', $data->id ) }}
                                    {{ Form::hidden('letter_template_id', $item->letter_template->id ) }}
                                    {{ Form::hidden('export_letter_id', $item->id ) }}
                                <div class='btn-group'>
                                    {!! Form::button('<i class="fa fa-eye-slash"></i>', ['data-toggle' => "modal" ,  
                                        'data-target' => "#previewModal",
                                        'data-backdrop' => "static" ,
                                        'data-keyboard' => "false", 
                                        'type' => 'button', 
                                        'class' => 'btn btn-success btn-xs' , 
                                        'onclick' => 'preview(this);',
                                        'data-claim_id' => $data->id,
                                        'data-letter_template_id' => $item->letter_template->id,
                                        'data-status' => $item->status,
                                        'data-id' => $item->id
                                        ]) 
                                    !!}
                                    {!! Form::button('<i class="fa fa-print"></i>', ['type' => 'submit', 'class' => 'btn btn-info btn-xs']) !!}
                                </div>
                                {!! Form::close() !!}
                                {{-- btn letter payment --}}
                                @if ($item->letter_template->letter_payment != null)
                                    {{ Form::open(array('url' => '/admin/exportLetterPDF', 'method' => 'POST')) }}
                                    @csrf
                                        {{ Form::hidden('claim_id', $data->id ) }}
                                        {{ Form::hidden('export_letter_id', $item->id ) }}
                                        {{ Form::hidden('letter_template_id', $item->letter_template->letter_payment ) }}
                                        {{ Form::hidden('id', $item->id ) }}
                                        {!! Form::button('<i class="fa fa-file-pdf-o"></i>', ['type' => 'submit', 'class' => 'btn btn-info btn-xs']) !!}
                                    {!! Form::close() !!} 
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
                

                {{-- //calculate --}}
                @foreach ($items as $item)
                    @php
                        if($item->reason_reject_id){
                            $sum += removeFormatPrice($item->amount);
                        }
                    @endphp
                @endforeach
                    
                <div class="row d-flex justify-content-end mt-5">
                    <button type="button"  class="btn btn-secondar col-md-3">Tổng Chi Phí Không Thanh Toán</button>
                    <p id="totalAmount" class="col-md-4 bg-danger p-2 m-0 font-weight-bold text-white">{{formatPrice($sum)}}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                
            </div>
            <div class="card-body">
                @if (count($items) > 0)
                <div >
                    <table class="table table-primary table-hover">
                        <tbody>
                            <tr>
                                <th>{{ __('message.content')}}</th>
                                <th>{{ __('message.amount')}}</th>
                                <th>{{ __('message.status')}}</th>
                                <th>{{ __('message.reason_reject')}}</th>
                            </tr>
                        
                        
                            @foreach ($items as $item)
                            <tr>
                                <td>{{$item->content}}</td>
                                <td>{{$item->amount}}</td>
                                @php
                                    $totalAmount += removeFormatPrice($item->amount);
                                @endphp
                                <td>
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input " disabled readonly {{ $item->reason_reject_id ? "" : 'checked' }}>
                                        <span class="custom-control-indicator"></span>
                                    </label>
                                </td>
                                <td>
                                    @if($item->reason_reject_id)
                                        <p class="text-danger font-weight-bold">{{ $item->reason_reject->name}}</p>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        
                    </table>
                    <div class="row d-flex justify-content-end">
                        <button type="button"  class="btn btn-secondar col-md-3">Total Amount</button>
                        <p id="totalAmount" class="col-md-4 bg-danger p-2 m-0 font-weight-bold text-white">{{formatPrice($totalAmount)}}</p>
                    </div>
                </div>
                
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal preview--}}
@include('claimManagement.previewModal')
{{-- noteOrEditModal--}}
@include('claimManagement.noteOrEditModal')

{{-- Modal viewFile--}}
@include('claimManagement.viewFileModal')

{{-- Modal comfirm payment--}}
@include('claimManagement.comfirmPaymentModal')

{{-- Modal request payment--}}
@include('claimManagement.requetPaymentModal')

{{-- Modal Debt Balance--}}
@include('claimManagement.debtBalanceModal')

{{-- Modal CSR File--}}
@include('claimManagement.csrModal')

{{-- closeClaimModal --}}
@include('claimManagement.closeClaimModal')

{{-- updateStatusEtalkModal --}}
@include('claimManagement.updateStatusEtalkModal')

{{-- deletePagesModal--}}
@include('claimManagement.deletePagesModal')

{{-- invoiceNoticationModal--}}
@include('claimManagement.invoiceNoticationModal')

{{-- sendMailCustomerModal--}}
@include('claimManagement.sendMailCustomerModal')

{{-- custommerConfirmModal--}}
@include('claimManagement.custommerConfirmModal')

@endsection


@section('scripts')
<script src="{{asset('js/fileinput.js?vision=') .$vision }}"></script>
<script src="{{ asset('js/format-price.js?vision=') .$vision }}"></script>
<script src="{{ asset('js/jquery-ui.js?vision=') .$vision }}"></script>
<script src="{{asset('js/popper.min.js?vision=') .$vision }}" ></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js?vision=') .$vision }}" ></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap4.min.js?vision=') .$vision }}" ></script>
<script src="{{ asset('js/tinymce.js?vision=') .$vision }}"></script>
<script src="{{ asset('js/jquery.tag-editor.min.js?vision=') .$vision }}"></script>
<script>
    $("#url_form_request").fileinput();
    function sendMailCustomerModal(e){
        var claim_id =  e.dataset.claim_id;
        var id = e.dataset.id;
        $(".loader").show();
        $('#letter_email_id').val(id);
        $('.claim_id').val(claim_id);
        $(".loader").fadeOut("slow");
        
    }
    function preview(e){
        $(".loader").show();
        var claim_id =  e.dataset.claim_id;
        var letter_template_id = e.dataset.letter_template_id;
        var status = e.dataset.status;
        var id = e.dataset.id;
        $('.status_letter').val(status).change();
        $('.export_letter_id').val(id);
        $('.ex_claim_id').val(claim_id);
        tinymce.get("preview_letter").setContent("");
        //CKEDITOR.instances['preview_letter'].setData("");
        $.ajax({
        url: '/admin/previewLetter',
        type: 'POST',
        context: e,
        data: {'claim_id' : claim_id , 'letter_template_id' : letter_template_id , 'export_letter_id' : id},
        })
        .done(function(res) {
            tinymce.get("preview_letter").setContent(res);
            //CKEDITOR.instances['preview_letter'].setData(res);
            $(".loader").fadeOut("slow");
        })
    }

    function noteOrEdit(e){
        var claim_id =  e.dataset.claim_id;
        var letter_template_id = e.dataset.letter_template_id;
        var status = e.dataset.status;
        var id = e.dataset.id;
        var note = e.dataset.note;
        var list_status = e.dataset.liststatus;
        if(status == {{ config('constants.statusExport.edit')}}){
            $('#button_save').show();
        }else{
            $('#button_save').hide();
        }
        $('.export_letter_id').val(id);
        $('.ex_claim_id').val(claim_id);
        $("#button_items").empty();
        $.each(JSON.parse(list_status), function( index, value ) {
            var res = value.match(/(Rejected)|(rejected)/g);
            if(res){
                addButton(index,value,'rejected');
            }else{
                var res2 = value.match(/(Approved)|(approved)/g);
                if(res2){
                    addButton(index,value,'approved');
                }else{
                addButton(index,value);
                }
            }
        });

        
        tinymce.get("note_letter").setContent(note);
        //CKEDITOR.instances['note_letter'].setData(note);
    }

    function addButton(value,text, type = 'none'){
        clone =  $("#button_clone").clone().html() ;
        
        clone = clone.replace("text_default", text);
        if(type == 'rejected'){
            clone = clone.replace("btn-secondary", 'btn-danger');
            clone = clone.replace("value_default", value+"-"+type);
        }else{
            clone = clone.replace("value_default", value+"-"+type);
        }
        $("#button_items").append(clone);
    }

    function viewFile(e){
        
        var claim_id =  e.dataset.claim_id;
        var letter_template_id = e.dataset.letter_template_id;
        var status = e.dataset.status;
        var id = e.dataset.id;
        var note = e.dataset.note;
        $('.status_letter').val(status).change();
        $('.export_letter_id').val(id);
        $('.ex_claim_id').val(claim_id);
        
        tinymce.get("approve_letter").setContent(note);
        //CKEDITOR.instances['approve_letter'].setData(note);
    }

    function comfirmPayment(e){
        $('.loader').show();
        var letter_template_id  = $("#letterTemplate option:selected").val();
        var letter_template_name  = $("#letterTemplate option:selected").text();
        $('#LetterTemplateId').val(letter_template_id);
        $("#textLetter").val(letter_template_name);
        $(".h_payment").remove();

        axios.get("{{ url('admin/getPaymentHistoryCPS') }}/{{$data->code_claim_show}}")
        .then(function (response) {
            $.each( response.data.data, function( key, value ) {
                addInputItem("Lần " + value.PAYMENT_TIME +". " + value.TF_DATE , formatPrice(value.TF_AMT));
            });
            $('#apv_hbs_in').val(formatPrice(response.data.approve_amt));
            //get info balance
            axios.get("{{ url('admin/getBalanceCPS') }}/{{$data->clClaim->member->memb_ref_no}}/{{$data->code_claim_show}}")
            .then(response => { 
                
                $('#PCV_EXPENSE').val(formatPrice(response.data.data.PCV_EXPENSE));
                $('#DEBT_BALANCE').val(formatPrice(response.data.data.DEBT_BALANCE));
            });

            $(".loader").fadeOut("slow");
            amount_letter_print();
        })
        .catch(function (error) {
            $(".loader").fadeOut("slow");
            alert(error);
            
        });
        
    }
    function addInputItem(text,amt){

        let clone =  '<tr class = "h_payment">' + $("#clone_item").clone().html() + '</tr>';
        //repalace name
        clone = clone.replace("_text_default", text);
        clone = clone.replace("_amount_default", "amount[]");
        clone = clone.replace("_amount_value_default", amt);
        $("#empty_item").before(clone);
        
    }
    function amount_letter_print(){
        var total = 0;
        var hbs_amt = parseInt(removeFormatPrice($('#apv_hbs_in').val()));
        var deduct = parseInt(removeFormatPrice($("#deduct").val() ? $("#deduct").val() : 0));
        var sumhis = 0;
        $(".history_amt").each(function( index ) {
            sumhis += parseInt(removeFormatPrice( $(this).val() ? $(this).val() : 0 )) ;
        });
        if($(".btn-method").text() == '+'){
            $('#amount_letter').val(formatPrice(hbs_amt + deduct - sumhis));
        }else{
            $('#amount_letter').val(formatPrice(hbs_amt - deduct - sumhis));
        }
        
    }
    function addNote(e){
        var id = e.dataset.id;
        var claim_id =  e.dataset.claim_id;
        $('.export_letter_id').val(id);
        $('.ex_claim_id').val(claim_id);
    
    }
    function upload_summary(){
        $(".loader").show();
        axios.get("{{route('sendSummaryEtalk', $data->id)}}")
        .then(function (response) {
            $(".loader").fadeOut("slow");
            console.log(response.data.status);
            var type = response.data.status == "error" ? 'danger' : response.data.status;
            $.notify({
                icon: 'fa fa-bell',
                title: '<strong>Hệ Thống</strong>',
                message: response.data.message
            },{
                placement: {
                    from: "top",
                    align: "right"
                },
                type: type
            });
            
        })
        .catch(function (error) {
            $(".loader").fadeOut("slow");
            alert(error);
        });
    }

    $(".disableRow").find("input,textarea,select").attr("disabled", "disabled");

    function sendMfile(){
        $(".loader").show();
        axios.get("{{route('sendMfile', $data->id)}}")
        .then(function (response) {
            $(".loader").fadeOut("slow");
            console.log(response.data);
            var type = response.data.errorCode != 0 ? 'danger' : 'success';
            var message
            $.notify({
                icon: 'fa fa-bell',
                title: '<strong>Thông Báo :  </strong>',
                message: response.data.errorMsg
            },{
                placement: {
                    from: "top",
                    align: "right"
                },
                type: type
            });
            if(response.data.errorCode == 0){
                $('#mfile-update-time').text('Mfile update successfully!')
            }
            
        })
        .catch(function (error) {
            $(".loader").fadeOut("slow");
            alert(error);
        });
    }

    $(document).ready(function () {
        $('.tag-editor').tagEditor({
            removeDuplicates: true
        });
        $('#debtBalanceTable').DataTable();
        $('.nav-toggle').click(function () {
            var collapse_content_selector = $(this).attr('href');
            var toggle_switch = $(this);
            $(collapse_content_selector).toggle(function () {
                if ($(this).css('display') == 'none') {
                    toggle_switch.html('▲');
                } else {
                    toggle_switch.html('▼');
                }
            });
        });

        $('.btn-method').click(function () {
            if($(this).text() == '+'){
                $(this).html('-')
            }else{
                $(this).html('+')
            }
            amount_letter_print();
        });

        var url_file_sorted =   '{{ $data->url_file_sorted ?  asset("") . config('constants.sotedClaimStorage') . $data->url_file_sorted . "?v=" . time()  : ''}}' ;
        var sorted_file_name = '{{$data->url_file_sorted}}';
        var sorted_file_ext = sorted_file_name !='' ? sorted_file_name.split(".")[1] : 'image';
        switch (sorted_file_ext.toLowerCase()) {
            case 'doc':
            case 'xls':
            case 'ppt':
                sorted_file_ext = 'office'
                break;
            case 'tif':
            case 'tiff':
                sorted_file_ext = 'gdocs'
                break;
            case 'pdf':
                sorted_file_ext = 'pdf'
                break;
            default:
                sorted_file_ext = 'image'
                break;
        }

        $("#url_file_sorted").fileinput({
            uploadAsync: false,
            
            maxFileCount: 1,
            overwriteInitial: true,
            initialPreview: [ url_file_sorted ],
            initialPreviewAsData: true, // identify if you are sending preview data only and not the raw markup
            initialPreviewFileType: 'image', // image is the default and can be overridden in config below
            initialPreviewDownloadUrl: 'https://kartik-v.github.io/bootstrap-fileinput-samples/samples/{filename}', // includes the dynamic `filename` tag to be replaced for each config
            initialPreviewConfig: [
                {type: sorted_file_ext, size: 8000, caption: sorted_file_name,  key: 1, downloadUrl: url_file_sorted}, // disable download
            ],
            purifyHtml: true, // this by default purifies HTML data for preview
            uploadExtraData: {
                img_key: "1000",
                img_keywords: "happy, places"
            }
        });
        $("input[name='vat_type[]']").change(function() {
            on_off_invoice();
        });
    });
    function on_off_invoice(){
        $(".vat_type").hide();
        
        if($("#original_invoice").prop( "checked" ) == true){
            $(".original_invoice").show();
        }
        if($("#e_invoice").prop( "checked" )  == true){
            $(".e_invoice").show();
        }
        if($("#converted_invoice").prop( "checked" ) == true){
            $(".converted_invoice").show();
        }
    }
</script>
@endsection
