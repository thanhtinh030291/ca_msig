@extends('layouts.admin.master')
@section('title', 'Report Admins')
@section('stylesheets')
    <link href="{{ asset('css/condition_advance.css') }}" media="all" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    @include('layouts.admin.breadcrumb_index', [
        'title'       => 'Report Admins',
        'page_name'   => 'Report Admins',
    ])
    <div class="row">
        <div class="col-md-12">
            {{-- <a class="btn btn-primary pull-right" href="{!! route('reportGop.create') !!}">
                {{ __('message.create')}}
            </a> --}}
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <form action="{!! route('reportGop.index') !!}" method="GET" class="form-horizontal" >
                <div class="card">
                    <div class="card-header">
                        <label  class="font-weight-bold" for="searchmail"> {{ __('message.search')}}</label>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                {{ Form::label('prov_name', 'Provider', ['class' => 'labelas']) }}
                                {{ Form::select('prov_name', $HBS_PV_PROVIDER, $search_params['prov_name'], ['id' => 'prov_name', 'class' => 'select2 form-control', 'placeholder' => ' ']) }}
                                {{ Form::label('tf_amt', 'Tranfer amt', ['class' => 'labelas']) }}
                                {{ Form::text('tf_amt', $search_params['tf_amt'], ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-info">{{ __('message.search') }}</button>
                        <button type="button" id="clearForm" class="btn btn-default">{{ __('message.reset') }}</button>
                    </div>
                </div>
                @include('layouts.admin.partials.condition_advance', ['limit_list' => $limit_list, 'limit' => $limit, 'list' => $reportGop, 'urlPost' => route('reportGop.index'), 'search_params' => $search_params])
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @if (count($reportGop) > 0)
                        {{ $reportGop->appends($search_params)->links() }}
                    @endif
                </div>
                <div class="card-body">
                    @if (count($reportGop) > 0)
                        <div class="table-responsive">
                            @include('report_gop.table')
                        </div>
                        {{ $reportGop->appends($search_params)->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="{{asset('js/lengthchange.js')}}"></script>
@endsection

