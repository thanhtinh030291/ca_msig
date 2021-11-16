<!-- Stored in resources/views/layouts/admin/partials/top_bar_navigation.blade.php -->

@extends('layouts.admin.master')
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="breadcrumb-holder">
            <h1 class="main-title float-left">{{ __('message.staff_management')}}</h1>
            <ol class="breadcrumb float-right">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">{{ __('message.staff_management')}}</li>
            </ol>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <a class="btn btn-primary pull-right" href="{{url('admin/admins/create')}}">
            {{ __('message.create_staff')}}
        </a>
    </div>
</div>
<br>
<!-- -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <label  class="font-weight-bold" for="searchmail"> {{ __('message.search')}}</label>
            </div>
            <div class="card-body">
                <form action="{{ url('admin/admins')}}" method="GET" class="form-horizontal">
                <div class="row">
                    <div class="col-md-6">
                        {{ Form::label('email', __('message.email'), array('class' => 'labelas')) }}
                        {{ Form::text('searchEmail',$email, ['class' => 'form-control']) }} <br/>
                        {{ Form::label('name', __('message.name'), array('class' => 'labelas')) }}
                        {{ Form::text('searchName', $name, ['class' => 'form-control']) }} <br/>
                    </div>
                    <div class="col-md-6">
                        <label>{{ __('message.start_date')}}</label>
                        {{ Form::text('searchCreated_at', isset($created_at) ? $created_at : '', ['class' => 'form-control datepicker bg-white']) }}<br>
                    </div>
                </div>
                <button type="submit" class="btn btn-info"> {{ __('message.search')}} </button>
                <button type="button" id="clearForm" class="btn btn-default"> {{ __('message.reset')}} </button>
                </form>
            </div>
        </div>
    </div>
</div>
<br>

<!-- staff list-->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
               <label class="font-weight-bold">{{ __('message.staff_list')}} | {{ __('message.total')}}: {{$admins->total()}} </label>
            </div>
            <div class="card-body">
                @if (count($admins) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <!-- Table Headings -->
                        <thead>
                            <tr>
                                <th>{{ __('message.id')}}</th>
                                <th>{{ __('message.email')}}</th>
                                <th>{{ __('message.name')}}</th>
                                <th>{{ __('message.date_created')}}</th>
                                <th class='text-center'>{{ __('message.control')}}</th>
                            </tr>
                        </thead>
                        <!-- Table Body -->
                        @foreach ($admins as $admin)
                        <tbody>
                            <tr>
                                <!-- staff info -->
                                <td>{{$admin->id}}</td>
                                <td>{{$admin->email}}</td>
                                <td>{{$admin->name}}</td>
                                <td>{{$admin->created_at}}</td>
                                <td class='text-center'>
                                    <!-- control -->
                                    <a class="btn btn-primary" href='{{url("admin/admins/$admin->id")}}'>{{__('message.view')}}</a>
                                    <a class="btn btn-success" href='{{url("admin/admins/$admin->id/edit")}}'>{{__('message.edit')}}</a>
                                    <button type="button" class="btn btn-danger btn-delete" data-url="{{ route('admins.destroy', $admin->id) }}"
                                        data-toggle="modal" data-target="#deleteConfirmModal"> {{__('message.delete')}}</button>
                                </td>
                            </tr>
                        </tbody>
                        @endforeach
                    </table>
                </div>
                {{ $admins->appends(['searchEmail' => $email, 'searchName' => $name , 'searchStatus' => $status , 'searchCreated_at' => $created_at])->links() }}
                Showing {{ $admins->firstItem() }} to {{ $admins->lastItem() }} of total {{$admins->total()}}
                @endif
            </div>
        </div>
    </div>
</div>

@include('layouts.admin.partials.delete_model', [
    'title'           => __('message.delete_staff_warning'),
    'confirm_message' => __('message.delete_staff_confirm'),
])

@endsection
@section('scripts')
<script src="{{asset('js/lengthchange.js?vision=') .$vision }}"></script>
@endsection
