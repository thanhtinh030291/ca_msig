@php
$langs = config('constants.lang');
$min = config('constants.minMaxLength.min');
$max = config('constants.minMaxLength.max');
@endphp
@extends('layouts.admin.master')
@section('title', __('message.product'))
@section('stylesheets')
<link rel="stylesheet" type="text/css" href="{{asset('css/jquery-ui.min.css?vision=') .$vision }}">
<link href="{{ asset('css/multi_lang.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
@include('layouts.admin.breadcrumb_index', [
    'title'       => __('message.edit_product'),
    'parent_url'  => route('product.index'),
    'parent_name' => __('message.edit_product'),
    'page_name'   => __('message.edit_product'),
])
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                {{ Form::open(array('url' => "admin/product/{$data->id}", 'method' => 'post' ,'class'=>'form-horizontal')) }}
                @method('PUT')
                {{ Form::label('name', __('message.name'), array('class' => 'labelas')) }} <span class="text-danger">*</span>
                {{ Form::text('name', $data->name, [ 'class' => 'form-control item-price','placeholder' => __('message.name'), 'required']) }}<br/>
                <div class="text-center tour-button">
                    <a class="btn btnt btn-secondary" href="{{url('admin/product')}}">
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
<script src="{{ asset('js/multi_lang.js?vision=') .$vision }}"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
@endsection
