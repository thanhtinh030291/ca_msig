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
            <h1 class="main-title float-left">{{ __('message.staff_create')}}</h1>
            <ol class="breadcrumb float-right">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="{{ url('admin/admins')}}">{{
                __('message.staff_management')}}</a></li>
                <li class="breadcrumb-item active">{{ __('message.staff_create')}}</li>
            </ol>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                {{ Form::open(array('url' => '/admin/admins', 'id' => 'frmCreateStaff', 'files' => true))}}
                <!-- Create account -->
                {{ Form::label('name',__('message.name'), array('class' => 'labelas')) }}<span class="text-danger">*</span>
                {{ Form::text('name', old('name'), ['class' => 'form-control', 'placeholder'=>__('message.enter_staff_name'), 'minlength' => $min, 'maxlength' => $max, 'required']) }}<br>

                {{ Form::label('email',__('message.email'), array('class' => 'labelas')) }}<span class="text-danger">*</span>
                {{ Form::email('email', old('email'), ['class' => 'form-control', 'placeholder'=>__('message.enter_staff_email'), 'maxlength' => "100", 'required']) }}<br>

                {{ Form::label('password',__('message.password'), array('class' => 'labelas')) }}<span class="text-danger">*</span>
                {{ Form::password('password',  ['type' => 'password','class' => 'form-control', 'placeholder'=>__('message.enter_staff_password'), 'minlength' => $min, 'maxlength' => $max, 'required']) }}<br>

                <input type="button" class="button" value="Generate password" onClick="generate();" tabindex="2"><br>

                {{ Form::label('role','Role', array('class' => 'labelas')) }}<span class="text-danger">*</span>
                {{ Form::select('_role', $all_roles_in_database,old('_role'), ['class' => 'select2 form-control', 'multiple' => 'multiple', 'name'=>'_role[]']) }}<br>
                

                <a class="btn btn-secondary" href="{{url('admin/admins')}}"> {{ __('message.back')}} </a>
                {{ Form::submit( __('message.save'),['class' => 'btn btn-primary center-block']) }}<br>
                <!-- End file image -->
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function randomPassword(length) {
    var chars = "abcdefghijklmnopqrstuvwxyz!@#$%&*()<>ABCDEFGHIJKLMNOP1234567890";
    var pass = "";
    for (var x = 0; x < length; x++) {
        var i = Math.floor(Math.random() * chars.length);
        pass += chars.charAt(i);
    }
    return pass;
}

function generate() {
    var ram = randomPassword(8)
    $("#password").val(ram);
}
</script>

@endsection
