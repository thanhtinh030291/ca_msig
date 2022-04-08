@extends('layouts.admin.master')
@section('title', 'Mfile Miss')
@section('stylesheets')
    <link href="{{ asset('css/condition_advance.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
@include('layouts.admin.breadcrumb_index', [
    'title'       => 'Miss M-files',
    'page_name'   => 'Miss M-files',
])

<br>
<div class="row">
    <div class="col-md-12">
        <form action="{{ url('admin/mfile') }}" method="GET" class="form-horizontal" >
            <div class="card">
                <div class="card-header">
                    <label  class="font-weight-bold" for="searchmail"> {{ __('message.search')}}</label>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            {{ Form::label('CL_NO', "Claim No", ['class' => 'labelas']) }}
                            {{ Form::text('code_claim_show', $search_params['code_claim_show'], ['class' => 'form-control']) }}
                            {{ Form::label('created_user', __('message.account_create'), ['class' => 'labelas']) }}
                            {{ Form::select('created_user', $admin_list, $search_params['created_user'], ['id' => 'created_user', 'class' => 'select2 form-control', 'placeholder' => ' ']) }}
                            
                            
                        </div>
                        <div class="col-md-6">
                            {{ Form::label('barcode', "barcode", ['class' => 'labelas']) }}
                            {{ Form::text('barcode', $search_params['barcode'], ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-info">{{ __('message.search') }}</button>
                    <button type="button" id="clearForm" class="btn btn-default">{{ __('message.reset') }}</button>
                </div>
            </div>
            @include('layouts.admin.partials.condition_advance', ['limit_list' => $limit_list, 'limit' => $limit, 'list' => $miss_mfile, 'urlPost' => route('product.index'), 'search_params' => $search_params])
        </form>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        {{ Form::open(array('url' => 'admin/mfile/check_all', 'method'=>'post', 'files' => true))}} 
        <button type="Send" class="btn btn-success" > Check All Claim</button> 
        {{ Form::close() }}
        
    </div>

    <div class="col-md-4">
    {{ Form::open(array('url' => 'admin/mfile/update_all', 'method'=>'post', 'files' => true))}} 
        <button type="Send" class="btn btn-success" > Update All Claim</button> 
    {{ Form::close() }}
   </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-header">
                    <label class="font-weight-bold">{{ __('message.claim_list')}} | {{ __('message.total')}}: {{$miss_mfile->total()}} </label>
                   
                </div>
                @if (count($miss_mfile) > 0)
                {{ $miss_mfile->appends($search_params)->links() }}
                @endif
            </div>
            <div class="card-body">
                @if (count($miss_mfile) > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <!-- Table Headings -->
                        <thead>
                            <tr>
                                <th>CL_NO</th>
                                <th>Barcode</th>
                                <th>MEMB_NAME</th>
                                <th>CLAIM TYPE</th>
                                <th>Code</th>
                                <th>Message</th>
                                <th>Version</th>
                                <th>Time</th>
                                <th>User</th>
                                <th class='text-center control_btn'>{{ 
                                    __('message.control')
                                }}</>
                            </tr>
                        </thead>
                        <!-- Table Body -->
                        @foreach ($miss_mfile as $value)
                        <tbody>
                            <tr>
                                <!-- ticket info -->
                                <td>{{ $value->code_claim_show }}</td>
                                <td>{{ $value->barcode }}</td>
                                <td>{{ $value->member_name }}</td>
                                <td>{{ $value->claim_type }}</td>
                                <td>{{ data_get($value,'LogMfile.m_errorCode') }}</td>
                                <td>{{ data_get($value,'LogMfile.m_errorMsg')  }}</td>
                                <td class="text-danger font-weight-bold">{{ data_get($value,'LogMfile.m_claim_file_latest_version') }}</td>
                                <td class="text-danger font-weight-bold">{{ data_get($value,'LogMfile.updated_at') }}</td>
                                <td>{{ data_get($admin_list,$value->created_user) }}</td>
                                <td class='text-center'>
                                    <!-- control -->
                                    <a class="btn btn-success" href='{{url("admin/claim/$value->id")}}' >Link</a>
                                    {{ Form::open(array('url' => 'admin/mfile/'.$value->id, 'method'=>'post', 'files' => true))}} 
                                    @method('PUT')
                                    <button type="Send" class="btn btn-success" > <i class="fa fa-mixcloud" aria-hidden="true"></i> Send To M-Files</button> 
                                    {{ Form::close() }}
                                </td>
                            </tr>
                        </tbody>
                        @endforeach
                    </table>
                </div>
                {{ $miss_mfile->appends($search_params)->links() }}
                @endif
            </div>
        </div>
    </div>
</div>

@include('layouts.admin.partials.delete_model', [
    'title'           => __('message.product_warning'),
    'confirm_message' => __('message.product_confirm'),
])

@endsection
@section('scripts')
<script src="{{asset('js/lengthchange.js?vision=') .$vision }}"></script>
@endsection
