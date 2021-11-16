@extends('layouts.admin.master')
@section('title', 'Level Role Status')
@section('stylesheets')
    <link href="{{ asset('css/fileinput.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-ui.min.css?vision=') .$vision }}">
    <link href="{{ asset('css/setting_date.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    @include('layouts.admin.breadcrumb_index', [
        'title'       => 'Level Role Status',
        'parent_url'  =>  route('levelRoleStatuses.index'),
        'parent_name' => 'Level Role Statuses',
        'page_name'   => 'Level Role Status',
    ])
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @include('level_role_statuses.show_fields')
                    <a href="{!! route('levelRoleStatuses.index') !!}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
