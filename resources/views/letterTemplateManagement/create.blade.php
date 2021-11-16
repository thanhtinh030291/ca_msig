
@extends('layouts.admin.master')
@section('title', __('message.create_letter_template'))
@section('stylesheets')
<link rel="stylesheet" type="text/css" href="{{asset('css/jquery-ui.min.css?vision=') .$vision }}">
<link href="{{ asset('css/multi_lang.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
<link href="{{ asset('css/ckeditor.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>

@endsection
@section('content')
@include('layouts.admin.breadcrumb_index', [
    'title'       => __('message.create_letter_template'),
    'parent_url'  => route('letter_template.index'),
    'parent_name' => __('message.letter_template'),
    'page_name'   => __('message.create_letter_template'),
])
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                {{ Form::open(array('url' => 'admin/letter_template', 'method' => 'post' ,'class'=>'form-horizontal')) }}
                
                    {{ Form::label('name', __('message.name'), array('class' => 'labelas')) }} <span class="text-danger">*</span>
                    {{ Form::text('name', old('name'), [ 'class' => 'form-control item-price','placeholder' => __('message.name'), 'required']) }}<br/>

                    {{ Form::label('level', 'Level Checking') }}
                    {{ Form::select('level',$list_level,old('level'), ['id' => 'template_reject', 'class' => 'form-control editor', 'placeholder'=>'Automatic']) }}<br>

                    {{ Form::label('level', 'Letter Payment') }}
                    {{ Form::select('letter_payment',$listLetter,old('letter_payment'), ['id' => 'template_reject', 'class' => 'form-control editor', 'placeholder'=>'None']) }}<br>

                    {{ Form::label('status_mantis', 'Status Of Mantics') }}
                    {{ Form::select('status_mantis',config('constants.status_mantic'),old('status_mantis'), ['id' => 'template_reject', 'class' => 'form-control editor', 'placeholder'=>'None']) }}<br>
                    
                    {{ Form::label('template', __('message.template')) }}
                    {{ Form::textarea('template', old('template'), ['id' => 'template_reject', 'class' => 'form-control editor']) }}<br>

                    {{ Form::label('claim_type', __('message.claim_type')) }}
                    {{ Form::select('claim_type', config('constants.claim_type'),old('claim_type'), ['id' => 'claim_type', 'class' => 'form-control ']) }}<br>
                <div class="text-center tour-button">
                    <a class="btn btnt btn-secondary" href="{{url('admin/letter_template')}}">
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
