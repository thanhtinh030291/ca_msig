@extends('layouts.admin.master')
@section('title', __('message.detail_role'))
@section('stylesheets')
    <link href="{{ asset('css/fileinput.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-ui.min.css?vision=') .$vision }}">
    <link href="{{ asset('css/setting_date.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
@include('layouts.admin.breadcrumb_index', [
    'title'       => __('message.detail_role'),
    'parent_url'  => route('role.index'),
    'parent_name' => __('message.detail_role'),
    'page_name'   => __('message.detail_role'),
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
                                {{ Form::label('_permissions', __('message.permissions'), array('class' => 'labelas')) }} <span class="text-danger">*</span>
                                {{ Form::select('_permissions', $all_permissions_in_database, $permissions, ['class' => ' select2 form-control', 'multiple' => 'multiple' , 'name' => '_permissions[]' ]) }}<br>
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
@section('scripts')
<script>
    $(".select2").select2({disabled:'readonly'});
    
</script>
@endsection
