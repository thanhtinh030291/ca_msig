@extends('layouts.admin.master')
@section('title', 'Provider')
@section('stylesheets')
<link rel="stylesheet" type="text/css" href="{{asset('css/jquery-ui.min.css')}}">
@endsection
@section('content')
    @include('layouts.admin.breadcrumb_index', [
        'title'       => 'Provider',
        'parent_url'  => route('providers.index'),
        'parent_name' => 'Providers',
        'page_name'   =>  'Provider',
    ])
    {!! Form::open(['route' => 'providers.store']) !!}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            {{ Form::label('name', __('message.id_claim'), array('class' => 'labelas')) }}<span class="text-danger">*</span>
                            {{ Form::select('_clam_oid',[], null, ['class' => 'code_claim form-control ','required']) }}
                        </div>
                        {{ Form::hidden('_claim_no', null, ['class' => ' form-control']) }}
                        <div class="col-md-4">
                            {{ Form::label('_amt', __('message.amount'), array('class' => 'labelas')) }}<span class="text-danger">*</span>
                            {{ Form::text('_amt', null, ['class' => 'item-price form-control','required']) }}
                        </div>
                        <div class="col-md-8">
                            {{ Form::label('_comment', __('message.description'), array('class' => 'labelas')) }}<span class="text-danger">*</span>
                            {{ Form::textarea('_comment', null, ['class' => ' form-control','required']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    
                        @include('providers.fields')
                    
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection
@section('scripts')
<script src="{{ asset('js/format-price.js?vision=') .$vision }}"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(window).load(function() {
        $('.code_claim').select2({          
            minimumInputLength: 2,
            ajax: {
            url: "/admin/dataAjaxHBSGOPClaim",
                dataType: 'json',
                data: function (params) {
                    return {
                        q: $.trim(params.term)
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
        $(document).on("change",".code_claim",function(){
            var id_code = $('.code_claim').val();
            var cl_no = $('.code_claim').text();
            $( "input[name*='_claim_no']" ).val(cl_no);
            $( "textarea[name*='_comment']" ).val('Ghi nợ thanh toán dư cho claim : ' + cl_no);
            if(id_code != null){
                $(".loader").show();
                axios.get("/admin/dataAjaxHBSProvByClaim/"+id_code)
                .then(function (response) {
                    $(".loader").fadeOut("slow");
                    $( "input[name*='PROV_CODE']" ).val(response.data.prov_code);
                    $( "input[name*='EFF_DATE']" ).val(response.data.eff_date);
                    $( "input[name*='TERM_DATE']" ).val(response.data.term_date);
                    $( "input[name*='PROV_NAME']" ).val(response.data.prov_name);
                    $( "input[name*='ADDR']" ).val(
                        response.data.addr1 ? response.data.addr1 : "" 
                        + response.data.addr2 ? "," + response.data.addr2 : ""
                        + response.data.addr3 ? "," + response.data.addr3 : ""
                        + response.data.addr4 ? "," + response.data.addr4 : "");
                    $( "input[name*='SCMA_OID_COUNTRY']" ).val(response.data.scma_oid_country);
                    $( "input[name*='PAYEE']" ).val( response.data.payee_first_name ?  response.data.payee_first_name : "" 
                    + response.data.payee_last_name ? " "+ response.data.payee_last_name : "");
                    $( "input[name*='CL_PAY_ACCT_NO']" ).val(response.data.cl_pay_acct_no);
                    $( "input[name*='BANK_NAME']" ).val(response.data.bank_name);
                    $( "input[name*='BANK_ADDR']" ).val(response.data.bank_addr);
                })
                .catch(function (error) {
                    $(".loader").fadeOut("slow");
                    alert(error);
                });
            }
        });
    });
    
</script>
@endsection
