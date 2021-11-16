@extends('layouts.admin.master')
@section('title', __('message.role_management'))
@section('stylesheets')
    <link href="{{ asset('css/condition_advance.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
@include('layouts.admin.breadcrumb_index', [
    'title'       => __('message.role_management'),
    'page_name'   => __('message.role_management'),
])
<div class="row">
    <div class="col-md-12">
        <a class="btn btn-primary pull-right" href="{{ url('admin/permiss/create') }}">
            {{ __('message.create')}}
        </a>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-12">
        <form action="{{ url('admin/permiss') }}" method="GET" class="form-horizontal" >
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
                    </div>
                    <br>
                    <button type="submit" class="btn btn-info">{{ __('message.search') }}</button>
                    <button type="button" id="clearForm" class="btn btn-default">{{ __('message.reset') }}</button>
                </div>
            </div>
            @include('layouts.admin.partials.condition_advance', ['limit_list' => $limit_list, 'limit' => $limit, 'list' => $data, 'urlPost' => route('role.index'), 'search_params' => $search_params])
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
                                <td>{{ $value->created_at }}</td>
                                <td>{{ $value->updated_at }}</td>
                                <td class='text-center'>
                                    <!-- control -->
                                    <a class="btn btn-primary" href='{{ url("admin/permiss/$value->id") }}'>{{ __('message.view') }}</a>
                                    <a class="btn btn-success" href='{{ url("admin/permiss/$value->id/edit") }}'>{{ __('message.edit') }}</a>
                                    <button type="button" class="btn btn-danger btn-delete" data-url="{{ route('permiss.destroy', $value->id) }}"
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
    'title'           => __('message.delete_warning'),
    'confirm_message' => __('message.delete_confirm'),
])

@endsection
@section('scripts')
<script src="{{asset('js/lengthchange.js?vision=') .$vision }}"></script>
@endsection
