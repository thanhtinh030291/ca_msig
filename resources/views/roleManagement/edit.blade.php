
@extends('layouts.admin.master')
@section('title', __('message.role_management'))
@section('stylesheets')
<link rel="stylesheet" type="text/css" href="{{asset('css/jquery-ui.min.css?vision=') .$vision }}">
@endsection
@section('content')
@include('layouts.admin.breadcrumb_index', [
    'title'       => __('message.edit_role'),
    'parent_url'  => route('role.index'),
    'parent_name' => __('message.edit_role'),
    'page_name'   => __('message.edit_role'),
])
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                {{ Form::open(array('url' => "admin/role/{$data->id}", 'method' => 'post' ,'class'=>'form-horizontal')) }}
                @method('PUT')
                <div class="text-center tour-button">
                    <a class="btn btnt btn-secondary" href="{{url('admin/role')}}">
                        {{ __('message.back')}}
                    </a>
                    <button type="submit" class="btn btnt btn-danger center-block"> {{__('message.save')}}</button> <br>
                </div>
                {{ Form::label('name', __('message.name'), array('class' => 'labelas')) }} <span class="text-danger">*</span>
                {{ Form::text('name', $data->name, [ 'class' => 'form-control item-price','placeholder' => __('message.name'), 'required']) }}<br/>

                {{ Form::label('_permissions', __('message.permissions'), array('class' => 'labelas')) }} <span class="text-danger">*</span>
                {{ Form::select('_permissions', $all_permissions_in_database, $permissions, ['class' => ' select2 form-control', 'multiple' => 'multiple' , 'name' => '_permissions[]' ]) }}
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="{{ asset('js/multi_lang.js?vision=') .$vision }}"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
@endsection
