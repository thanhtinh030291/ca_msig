<!-- Stored in resources/views/layouts/admin/partials/top_bar_navigation.blade.php -->
@extends('layouts.admin.master')
@section('title', __('message.form_claim_M'))
@section('stylesheets')
    <link href="{{ asset('css/condition_advance.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/ion.rangeSlider.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
@include('layouts.admin.breadcrumb_index', [
    'title'       => "EXPORT",
    'page_name'   => "EXPORT",
])
<div class="row">
    <div class="col-md-12">
        <a class="btn btn-primary pull-right" href="{{url('admin/claim/create')}}">
            {{ __('message.create_claim')}}
        </a>
    </div>
</div>
<br>

<div class="row">
    <div class="col-md-12">
        {{ Form::open(array('url' => '/admin/claimExport', 'method' => 'get')) }}
        <div class="card">
            <div class="card-header">
                <label  class="font-weight-bold" for="search"> {{ __('message.search')}}</label>
            </div>
            <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            {{ Form::label('created_at_from', __('message.date_created') . ' From', ['class' => 'labelas']) }}
                            {{ Form::text('created_at_from', data_get($finder,'created_at_from'), ['class' => 'form-control datepicker','required']) }}
                            {{ Form::label('created_at_to', __('message.date_created'). ' To', ['class' => 'labelas']) }}
                            {{ Form::text('created_at_to', data_get($finder,'created_at_to'), ['class' => 'form-control datepicker', 'required']) }}
                        </div>
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4">
                            
                        </div>
                    </div>
                    <br>
                {{ Form::submit( __('message.search'), ['class' => 'btn btn-info']) }}
                <button type="submit" name="export" value="yes" class="btn btn-success"> EXPORT</button> 
                <button type="button" id="clearForm" class="btn btn-default"> {{ __('message.reset')}}</button>    
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                
            </div>
            <div class="card-body">
                @if (count($datas) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('message.id_claim')}}</th>
                                <th>{{ __('message.account_create')}}</th>
                                <th>{{ __('message.account_edit')}}</th>
                                <th>{{ __('message.date_created')}}</th>
                                <th>{{ __('message.date_updated')}}</th>
                            </tr>
                        </thead>
                        @foreach ($datas as $data)
                        <tbody>
                            <tr>
                                <td>
                                    {{$data->code_claim_show}}
                                </td>
                                <td>{{ $admin_list[$data->created_user] }}</td>
                                <td>{{ $admin_list[$data->updated_user] }}</td>
                                <td>{{ $data->created_at }}</td>
                                <td>{{ $data->updated_at }}</td>
                            </tr>
                        </tbody>
                        @endforeach
                    </table>
                </div>
                {{ $datas->appends($finder)->links()}}
                {{ __('message.showing') .' '. $datas->firstItem() .' '. __('message.to') .' '. $datas->lastItem() .' '. __('message.of_total') .' '. $datas->total() }}
                @endif
            </div>
        </div>
    </div>
</div>



@include('layouts.admin.partials.delete_model', [
    'title'           => __('message.delete_claim_confirm'),
    'confirm_message' => __('message.delete_claim_confirm'),
])
@endsection

@section('scripts')
<script src="{{asset('js/lengthchange.js?vision=') .$vision }}"></script>
<script src="{{asset('js/jquery.imgareaselect.pack.js?vision=') .$vision }}"></script>
<script src="{{ asset('js/ion.rangeSlider.min.js?vision=') .$vision }}"></script>
<script src="{{ asset('js/format-price.js?vision=') .$vision }}"></script>
<script>
    //ajax select code
$(window).load(function() {
    $('.code_claim').select2({          
        minimumInputLength: 2,
        ajax: {
        url: "/admin/dataAjaxHBSClaim",
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

    $('.prov_name').select2({          
        minimumInputLength: 2,
        ajax: {
        url: "/admin/dataAjaxHBSProv",
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
    
    $("#rangePrimary").ionRangeSlider({
    type: "double",
    grid: true,
    min: 0,
    max: 1000000000,
    from: <?= data_get($finder, 'budget') ? explode(";", data_get($finder, 'budget'))[0] : 0 ?>,
    to: <?= data_get($finder, 'budget') ? explode(";", data_get($finder, 'budget'))[1] : 300000000 ?> ,
    prefix: ""
    });
    $("#rangePrimary").on("change", function () {
        var $this = $(this),
        value = $this.prop("value").split(";");
        var minPrice = value[0];
        var maxPrice = value[1];
        $("#priceRangeSelected").text("Từ " + formatPrice(minPrice) + " đ,  đến " + formatPrice(maxPrice) + "đ");
    });
});
</script>

@endsection