@extends('layouts.admin.master')
@section('title', __('message.detail_reason_inject'))
@section('stylesheets')
    <link href="{{ asset('css/fileinput.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-ui.min.css?vision=') .$vision }}">
    <link href="{{ asset('css/setting_date.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
@include('layouts.admin.breadcrumb_index', [
    'title'       => __('message.detail_reason_inject'),
    'parent_url'  => route('reason_reject.index'),
    'parent_name' => __('message.detail_reason_inject'),
    'page_name'   => __('message.detail_reason_inject'),
])
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <td>
                                <p class="font-weight-bold">
                                    {{ __('message.name')}}
                                    <b class="text-danger">*</b>
                                </p>
                                <div> {{ $data->name }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class="font-weight-bold">{{ __('message.account_create') }}
                                <div> {{ $userCreated }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class="font-weight-bold">{{ __('message.account_edit') }}
                                <div> {{ $userUpdated }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class="font-weight-bold">{{ __('message.date_created') }}
                                <div> {{ $data->created_at }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class="font-updated_at-bold">{{ __('message.date_updated') }}
                                <div> {{ $data->updated_at }}</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                </div>
                <a class="btn btn-secondary" 
                    href="{{ url('admin/reason_reject') }}">{{ __('message.back')}}</a>
                <br>
            </div>
        </div>
    </div>
</div>
@endsection
