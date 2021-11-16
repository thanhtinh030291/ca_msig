@extends('layouts.admin.master')
@section('title', 'Payment History')
@section('stylesheets')
    <link href="{{ asset('css/fileinput.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-ui.min.css?vision=') .$vision }}">
    <link href="{{ asset('css/setting_date.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
    <style>
        .modal {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 900000;
            display: none;
            overflow: hidden;
            outline: 0;
        }
    </style>
@endsection
@section('content')
@include('layouts.admin.breadcrumb_index', [
    'title'       => 'Payment History',
    'parent_url'  => route('PaymentHistory.index'),
    'parent_name' => 'Payment History',
    'page_name'   => 'Payment History',
])
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                Payment Info
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <p class="tinh">
                            PAYM_ID : {{ $data->PAYM_ID }}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p class="tinh">
                            CL_NO : {{ $data->CL_NO }}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p class="tinh">
                            MEMB_NAME : {{ $data->MEMB_NAME }}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p class="tinh">
                            POCY_REF_NO : {{ $data->POCY_REF_NO }}
                        </p>
                    </div><div class="col-md-3">
                        <p class="tinh">
                            MEMB_REF_NO : {{ $data->MEMB_REF_NO }}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p class="tinh">
                            PRES_AMT : <span class="text-danger font-weight-bold">{{ formatPrice($data->PRES_AMT) }}</span>
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p class="tinh">
                            APP_AMT : <span class="text-danger font-weight-bold">{{ formatPrice($data->APP_AMT) }}</span>
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p class="tinh">
                            TF_AMT : <span class="text-danger font-weight-bold">{{ formatPrice($data->TF_AMT) }}</span> 
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p class="tinh">
                            DEDUCT_AMT : <span class="text-danger font-weight-bold">{{ formatPrice($data->DEDUCT_AMT) }}</span>
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p class="tinh">
                            PAYMENT_METHOD : {{ $data->PAYMENT_METHOD }}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p class="tinh">
                            CL_NO : {{ $data->CL_NO }}
                        </p>
                    </div><div class="col-md-3">
                        <p class="tinh">
                            MANTIS_ID : {{ $data->MANTIS_ID }}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p class="tinh">
                            ACCT_NAME : {{ $data->ACCT_NAME }}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p class="tinh">
                            ACCT_NO : {{ $data->ACCT_NO }}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p class="tinh">
                            BANK_NAME : {{ $data->BANK_NAME }}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p class="tinh">
                            BANK_CITY : {{ $data->BANK_CITY }}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p class="tinh">
                            BANK_BRANCH : {{ $data->BANK_BRANCH }}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p class="tinh">
                            BENEFICIARY_NAME : {{ $data->BENEFICIARY_NAME }}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p class="tinh">
                            PP_DATE : {{ $data->PP_DATE }}
                        </p>
                    </div><div class="col-md-3">
                        <p class="tinh">
                            PP_PLACE : {{ $data->PP_PLACE }}
                        </p>
                    </div>

                    <div class="col-md-3">
                        <p class="tinh">
                            CL_NO : {{ $data->CL_NO }}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p class="tinh">
                            PP_NO : {{ $data->PP_NO }}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p class="tinh">
                            CL_TYPE : {{ $data->CL_TYPE }}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p class="tinh">
                            BEN_TYPE : {{ $data->BEN_TYPE }}
                        </p>
                    </div><div class="col-md-3">
                        <p class="tinh">
                            PAYMENT_TIME : {{ $data->PAYMENT_TIME }}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p class="tinh">
                            TF_STATUS : {{ $data->TF_STATUS }}
                        </p>
                    </div>

                    <div class="col-md-3">
                        <p class="tinh">
                            TF_DATE : {{ $data->TF_DATE }}
                        </p>
                    </div><div class="col-md-3">
                        <p class="tinh">
                            VCB_SEQ : {{ $data->VCB_SEQ }}
                        </p>
                    </div>

                    <div class="col-md-3">
                        <p class="tinh">
                            VCB_CODE : {{ $data->VCB_CODE }}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p class="tinh">
                            PAYM_ID : {{ $data->PAYM_ID }}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p class="tinh">
                            claim_id : {{ $data->claim_id }}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p class="tinh">
                            Letter : @if($data->url_letter) <button data-file="{{ $data->url_letter }}" onclick="viewfile(this);"><i class="fa fa-eye"></i></button> @endif
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p class="tinh">
                            Payment : @if($data->url_payment) <button data-file="{{ $data->url_payment }}" onclick="viewfile(this);"><i class="fa fa-eye"></i></button> @endif
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p class="tinh">
                            Ủy Nhiệm Chi  : @if( $data->url_unc ) <button data-file="{{ $data->url_unc }}" onclick="viewfile(this);"><i class="fa fa-eye"></i></button> @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-2">
        <div class="card">
            <div class="card-header">
                HBS Log at the time of payment 
            </div>
            <div class="card-body">
                @php
                    dump(json_decode($data->HBS,true));    
                @endphp
            </div>
        </div>
    </div>
    
    <a class="btn btn-secondary" 
        href="{{ url('admin/PaymentHistory') }}">{{ __('message.back')}}</a>
    <br>
</div>
<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="unc_file" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">View File</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <embed  id='link_file' src="" width="100%" height="500" type="application/pdf">
        
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
    </div>
</div>
</div>
<!-- End Modal -->
@endsection
@section('scripts')
<script>
    function viewfile(e) {
        var dir = "{{ asset('/storage/payment/') }}"+"/";
        var url = e.dataset.file;
        var parent = $('#link_file').parent();
        var newElement = "<embed src='"+dir+url+"' id='link_file' width='780' height='500' >";
        $('#link_file').remove();
        parent.append(newElement);
        $( '#unc_file' ).modal( 'toggle' );
    }
</script>
@endsection