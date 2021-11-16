@extends('layouts.admin.master')
@section('title', 'Role Change Status')
@section('stylesheets')
    <link href="{{ asset('css/fileinput.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-ui.min.css?vision=') .$vision }}">
    <link href="{{ asset('css/setting_date.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    @include('layouts.admin.breadcrumb_index', [
        'title'       => 'Role Change Status',
        'parent_url'  =>  route('roleChangeStatuses.index'),
        'parent_name' => 'Role Change Statuses',
        'page_name'   => 'Role Change Status',
    ])
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @include('role_change_statuses.show_fields')
                    <a href="{!! route('roleChangeStatuses.index') !!}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
