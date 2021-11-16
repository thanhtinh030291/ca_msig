@extends('layouts.admin.master')
@section('title', 'Providers')
@section('stylesheets')
    <link href="{{ asset('css/condition_advance.css') }}" media="all" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    @include('layouts.admin.breadcrumb_index', [
        'title'       => 'Providers',
        'page_name'   => 'Providers',
    ])
    <div class="row">
        <div class="col-md-12">
            <a class="btn btn-primary pull-right" href="{!! route('providers.create') !!}">
                <p>Add Deduct to Provider</p>
            </a>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <form action="{!! route('providers.index') !!}" method="GET" class="form-horizontal" >
                <div class="card">
                    <div class="card-header">
                        <label  class="font-weight-bold" for="searchmail"> {{ __('message.search')}}</label>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                
                                {{ Form::label('PROV_CODE', "Provider Code", ['class' => 'labelas']) }}
                                {{ Form::text('PROV_CODE', $search_params['PROV_CODE'], ['class' => 'form-control']) }}
                                {{ Form::label('created_at', __('message.date_created'), ['class' => 'labelas']) }}
                                {{ Form::text('created_at', $search_params['created_at'], ['class' => 'form-control datepicker']) }}
                            </div>
                            <div class="col-md-6">
                                {{ Form::label('PROV_NAME', "Provider Name", ['class' => 'labelas']) }}
                                {{ Form::text('PROV_NAME', $search_params['PROV_NAME'], ['class' => 'form-control']) }}
                                {{ Form::label('updated_at', __('message.date_updated'), ['class' => 'labelas']) }}
                                {{ Form::text('updated_at', $search_params['updated_at'], ['class' => 'form-control datepicker']) }}
                            </div>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-info">{{ __('message.search') }}</button>
                        <button type="button" id="clearForm" class="btn btn-default">{{ __('message.reset') }}</button>
                    </div>
                </div>
                @include('layouts.admin.partials.condition_advance', ['limit_list' => $limit_list, 'limit' => $limit, 'list' => $providers, 'urlPost' => route('providers.index'), 'search_params' => $search_params])
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @if (count($providers) > 0)
                        {{ $providers->appends($search_params)->links() }}
                    @endif
                </div>
                <div class="card-body">
                    @if (count($providers) > 0)
                        <div class="table-responsive">
                            @include('providers.table')
                        </div>
                        {{ $providers->appends($search_params)->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{ $providers->appends($search_params)->links() }} 
@endsection
@section('scripts')
    <script src="{{asset('js/lengthchange.js')}}"></script>
@endsection

