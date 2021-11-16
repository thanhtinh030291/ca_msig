
@extends('layouts.admin.master')
@section('title', __('message.create_reason_inject'))
@section('stylesheets')
<link rel="stylesheet" type="text/css" href="{{asset('css/jquery-ui.min.css?vision=') .$vision }}">
<link href="{{ asset('css/multi_lang.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
<link href="{{ asset('css/ckeditor.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
@include('layouts.admin.breadcrumb_index', [
    'title'       => __('message.create_reason_inject'),
    'parent_url'  => route('reason_reject.index'),
    'parent_name' => __('message.reason_reject'),
    'page_name'   => __('message.create_reason_inject'),
])
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                {{ Form::open(array('url' => 'admin/reason_reject', 'method' => 'post' ,'class'=>'form-horizontal')) }}
                
                    {{ Form::label('name', __('message.name'), array('class' => 'labelas')) }} <span class="text-danger">*</span>
                    {{ Form::text('name', old('name'), [ 'class' => 'form-control item-price','placeholder' => __('message.name'), 'required']) }}<br/>

                    {{ Form::label('term', __('message.term'), array('class' => 'labelas')) }} 
                    {{ Form::select('term_id', $listTerm, old('term_id'), ['class' => ' select2 form-control', 'placeholder' => 'None']) }}<br>

                    {{ Form::label('template', __('message.template')) }}
                    {{ Form::textarea('template', old('template'), ['id' => 'template_reject', 'class' => 'form-control editor2']) }}<br>
                <div class="text-center tour-button">
                    <a class="btn btnt btn-secondary" href="{{url('admin/reason_reject')}}">
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

<script src="{{ asset('js/tinymce.js?vision=') .$vision }}"></script>

@endsection
