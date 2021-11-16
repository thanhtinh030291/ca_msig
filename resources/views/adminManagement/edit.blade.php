<!-- Stored in resources/views/layouts/admin/partials/top_bar_navigation.blade.php -->
<?php 
$min = Config::get('constants.minMaxLength.min');
$max = Config::get('constants.minMaxLength.max');
?>
@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="breadcrumb-holder">
            <h1 class="main-title float-left">{{ __('message.staff_edit')}}</h1>
            <ol class="breadcrumb float-right">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="{{ url('admin/admins')}}">{{ __('message.staff_management')}}</a></li>
                <li class="breadcrumb-item active">{{ __('message.staff_edit')}}</li>
            </ol>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                {{ Form::open(array('url' => '/admin/admins/'.$user->id, 'method'=>'post', 'id' => 'frmUpdateStaff', 'files' => true))}} @method('PUT')
                    <!-- Staff info -->
                    {{ Form::label('name',__('message.name'), array('class' => 'labelas')) }}
                    {{ Form::text('name', $user->name, ['class' => 'form-control','placeholder'=>__('message.enter_staff_name'), 'minlength' => $min, 'maxlength' => $max, 'required']) }}<br>
                    {{ Form::label('email',__('message.email'), array('class' => 'labelas')) }}
                    {{ Form::text('email', $user->email, ['class' => 'form-control','placeholder'=>__('message.enter_staff_email'), 'maxlength' => "100", 'required', 'readonly']) }}<br>

                    {{ Form::label('role','Role', array('class' => 'labelas')) }}<span class="text-danger">*</span>
                    {{ Form::select('_role', $all_roles_in_database, $user->roles->pluck('name'), ['class' => 'select2 form-control', 'multiple' => 'multiple', 'name'=>'_role[]']) }}<br>
                    
                   

                    <!-- Add update Button -->
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            <a class="btn btn-secondary" href="{{url('admin/admins')}}"> {{ __('message.back')}} </a>
                            {{ Form::submit( __('message.save'),['class' => 'btn btn-primary center-block']) }}<br>
                        </div>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
   
</script>
@endsection
