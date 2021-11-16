<!-- Stored in resources/views/layouts/admin/partials/top_bar_navigation.blade.php -->

@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="breadcrumb-holder">
            <h1 class="main-title float-left">{{ __('message.staff_view')}}</h1>
            <ol class="breadcrumb float-right">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="{{ url('admin/admins')}}">{{
                        __('message.staff_management')}} </a></li>
                <li class="breadcrumb-item active">{{ __('message.staff_view')}}</li>
            </ol>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                            <!-- Table Headings -->
                        <thead>
                            <tr>
                                <th>{{ __('message.id')}} : {{$user->id}}</th>
                            </tr>
                        </thead>
                        <!-- Table Body -->
                        <tbody>
                            <tr>
                                <!-- Task Name -->
                                <td><p class="font-weight-bold">{{ __('message.staff_name')}} </p><div> {{ $user->name }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td><p class="font-weight-bold">{{ __('message.email')}}</p> <div> {{ $user->email }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td><p class="font-weight-bold">{{ __('message.date_created')}}</p> <div> {{ $user->created_at }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td><p class="font-weight-bold">{{ __('message.date_updated')}}</p> <div> {{ $user->updated_at }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td><p class="font-weight-bold">Roles</p> 
                                    {{ Form::select('_role', $all_roles_in_database, $user->roles->pluck('name'), ['class' => 'select2 form-control', 'multiple' => 'multiple', 'disable']) }}<br>
                                </td>
                            </tr>
                            <tr>
                                <td><p class="font-weight-bold">Permission</p> 
                                    {{ Form::select('_role', $all_permissions_in_database, $user->getAllPermissions()->pluck('name'), ['class' => ' select2 form-control', 'multiple' => 'multiple', 'disable']) }}<br>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <a class="btn btn-secondary" href="{{url('admin/admins')}}">
                        {{ __('message.back')}}
                    </a>
                </div>
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