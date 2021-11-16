@extends('layouts.admin.master')
@section('title', __('message.letter_template'))
@section('stylesheets')
    <link href="{{ asset('css/condition_advance.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
@include('layouts.admin.breadcrumb_index', [
    'title'       => __('message.letter_template'),
    'page_name'   => __('message.letter_template'),
])
<div class="row">
    <div class="col-md-12">
        <a class="btn btn-primary pull-right" href="{{ url('admin/letter_template/create') }}">
            {{ __('message.create')}}
        </a>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-12">
        <form action="{{ url('admin/letter_template') }}" method="GET" class="form-horizontal" >
            <div class="card">
                <div class="card-header">
                    <label  class="font-weight-bold" for="searchmail"> {{ __('message.search')}}</label>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            {{ Form::label('name_en', __('message.name'), ['class' => 'labelas']) }}
                            {{ Form::text('name', $search_params['name'], ['class' => 'form-control']) }}
                        </div>
                        {{-- <div class="col-md-6">
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
                        </div> --}}
                    </div>
                    <br>
                    <button type="submit" class="btn btn-info">{{ __('message.search') }}</button>
                    <button type="button" id="clearForm" class="btn btn-default">{{ __('message.reset') }}</button>
                </div>
            </div>
            @include('layouts.admin.partials.condition_advance', ['limit_list' => $limit_list, 'limit' => $limit, 'list' => $data, 'urlPost' => route('letter_template.index'), 'search_params' => $search_params])
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                @if (count($data) > 0)
                {{ $data->appends($search_params)->links() }}
                @endif
            </div>
            <div class="card-body">
                @if (count($data) > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <!-- Table Headings -->
                        <thead>
                            <tr>
                                <th>{{ __('message.name')}}</th>
                                <th>{{__('message.claim_type')}}</th>
                                <th>Level Change status</th>
                                <th>{{ __('message.account_create')}}</th>
                                <th>{{ __('message.account_edit')}}</th>
                                <th>{{ __('message.date_created')}}</th>
                                <th>{{ __('message.date_updated')}}</th>
                                <th class='text-center control_btn'>{{ 
                                    __('message.control')
                                }}</th>
                            </tr>
                        </thead>
                        <!-- Table Body -->
                        @foreach ($data as $value)
                        <tbody>
                            <tr>
                                <!-- ticket info -->
                                <td>{{ $value->name }}</td>
                                <td>{{ data_get(config('constants.claim_type'),$value->claim_type) }}</td>
                                <td>{{ data_get($list_level, $value->level, 'Automatic')}}</td>
                                <td>{{ $admin_list[$value->created_user] }}</td>
                                <td>{{ $admin_list[$value->updated_user] }}</td>
                                <td>{{ $value->created_at }}</td>
                                <td>{{ $value->updated_at }}</td>
                                <td class='text-center'>
                                    <!-- control -->
                                    <a class="btn btn-primary" href='{{ url("admin/letter_template/$value->id") }}'>{{ __('message.view') }}</a>
                                    <a class="btn btn-success" href='{{ url("admin/letter_template/$value->id/edit") }}'>{{ __('message.edit') }}</a>
                                    <button type="button" class="btn btn-danger btn-delete" data-url="{{ route('letter_template.destroy', $value->id) }}"
                                        data-toggle="modal" data-target="#deleteConfirmModal">{{ __('message.delete') }}</button>
                                </td>
                            </tr>
                        </tbody>
                        @endforeach
                    </table>
                </div>
                {{ $data->appends($search_params)->links() }}
                @endif
            </div>
        </div>
    </div>
</div>

@include('layouts.admin.partials.delete_model', [
    'title'           => __('message.delete_reason_inject_warning'),
    'confirm_message' => __('message.delete_reason_inject_confirm'),
])

@endsection
@section('scripts')
<script src="{{asset('js/lengthchange.js?vision=') .$vision }}"></script>
@endsection
