
@extends('layouts.admin.master')
@section('title', __('message.create_role'))
@section('stylesheets')
<link rel="stylesheet" type="text/css" href="{{asset('css/jquery-ui.min.css?vision=') .$vision }}">
@endsection
@section('content')
@include('layouts.admin.breadcrumb_index', [
    'title'       => __('message.create_role'),
    'parent_url'  => route('role.index'),
    'parent_name' => __('message.role_management'),
    'page_name'   => __('message.create_role'),
])
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                {{ Form::open(array('url' => 'admin/permiss', 'method' => 'post' ,'class'=>'form-horizontal')) }}
                
                    {{ Form::label('name', __('message.name'), array('class' => 'labelas')) }} <span class="text-danger">*</span>
                    {{ Form::text('name', old('name'), [ 'class' => 'form-control item-price','placeholder' => __('message.name'), 'required']) }}<br/>
                <div class="text-center tour-button">
                    <a class="btn btnt btn-secondary" href="{{url('admin/permiss')}}">
                        {{ __('message.back')}}
                    </a>
                    <button type="submit" class="btn btnt btn-danger center-block"> {{__('message.save')}}</button> <br>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

@endsection
