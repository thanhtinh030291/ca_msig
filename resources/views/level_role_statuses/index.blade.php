@extends('layouts.admin.master')
@section('title', 'Level Role Statuses')
@section('stylesheets')
    <link href="{{ asset('css/condition_advance.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    @include('layouts.admin.breadcrumb_index', [
        'title'       => 'Level Role Statuses',
        'page_name'   => 'Level Role Statuses',
    ])
    <div class="row">
        <div class="col-md-12">
            <a class="btn btn-primary pull-right" href="{!! route('levelRoleStatuses.create') !!}">
                {{ __('message.create')}}
            </a>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <form action="{!! route('levelRoleStatuses.index') !!}" method="GET" class="form-horizontal" >
                <div class="card">
                    <div class="card-header">
                        <label  class="font-weight-bold" for="searchmail"> {{ __('message.search')}}</label>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                {{ Form::label('created_user', __('message.account_create'), ['class' => 'labelas']) }}
                                {{ Form::select('created_user', $admin_list, $search_params['created_user'], ['id' => 'created_user', 'class' => 'select2 form-control', 'placeholder' => ' ']) }}
                                {{ Form::label('created_at', __('message.date_created'), ['class' => 'labelas']) }}
                                {{ Form::text('created_at', $search_params['created_at'], ['class' => 'form-control datepicker']) }}
                            </div>
                            <div class="col-md-6">
                                {{ Form::label('updated_user', __('message.account_edit'), ['class' => 'labelas']) }}
                                {{ Form::select('updated_user', $admin_list, $search_params['updated_user'], ['id' => 'updated_user', 'class' => 'select2 form-control', 'placeholder' => ' ']) }}
                                {{ Form::label('updated_at', __('message.date_updated'), ['class' => 'labelas']) }}
                                {{ Form::text('updated_at', $search_params['updated_at'], ['class' => 'form-control datepicker']) }}
                            </div>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-info">{{ __('message.search') }}</button>
                        <button type="button" id="clearForm" class="btn btn-default">{{ __('message.reset') }}</button>
                    </div>
                </div>
                @include('layouts.admin.partials.condition_advance', ['limit_list' => $limit_list, 'limit' => $limit, 'list' => $levelRoleStatuses, 'urlPost' => route('levelRoleStatuses.index'), 'search_params' => $search_params])
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @if (count($levelRoleStatuses) > 0)
                        {{ $levelRoleStatuses->appends($search_params)->links() }}
                    @endif
                </div>
                <div class="card-body">
                    @if (count($levelRoleStatuses) > 0)
                        <div class="table-responsive">
                            @include('level_role_statuses.table')
                        </div>
                        {{ $levelRoleStatuses->appends($search_params)->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{ $levelRoleStatuses->appends($search_params)->links() }} 
@endsection
@section('scripts')
    <script src="{{asset('js/lengthchange.js?vision=') .$vision }}"></script>
@endsection

