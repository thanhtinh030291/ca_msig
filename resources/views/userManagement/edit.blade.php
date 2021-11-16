@php
$langs = config('constants.lang');
$min = config('constants.minMaxLength.min');
$max = config('constants.minMaxLength.max');
@endphp
@extends('layouts.admin.master')
@section('title','My Profile')
@section('stylesheets')
<link rel="stylesheet" type="text/css" href="{{asset('css/jquery-ui.min.css?vision=') .$vision }}">
<link href="{{ asset('css/multi_lang.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
<link href="{{ asset('css/ckeditor.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
<link href="{{ asset('css/drawingboard.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
@include('layouts.admin.breadcrumb_index', [
    'title'       => 'My Profile',
    'parent_url'  => route('home'),
    'parent_name' => "Home",
    'page_name'   => 'My Profile',
])
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <!-- Start content -->
                <div class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="breadcrumb-holder">
                                    <h1 class="main-title float-left">My Profile</h1>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                            <!-- end row -->
                            <div class="row">
                                
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">						
                                            <div class="card mb-3">
                                                <div class="card-header">
                                                    <h3><i class="fa fa-user"></i> Profile details</h3>								
                                                </div>
                                                    
                                                <div class="card-body">
                                                    
                                                    
                                                    {{ Form::open(array('url' => "admin/users/update", 'method' => 'post' ,'files' => true, 'id' => 'drawing-form')) }}
                                    
                                                    <div class="row">	
                                                    
                                                    <div class="col-lg-9 col-xl-9">
                                                        
                                                        <div class="row">				
                                                            <div class="col-lg-6">
                                                            <div class="form-group">
                                                                {{ Form::label('name','Full Name', array('class' => 'labelas')) }} <span class="text-danger">*</span>
                                                                {{ Form::text('name', $user->name, [ 'class' => 'form-control ','placeholder' => __('message.name'), 'required']) }}<br/>
                                                            </div>
                                                            </div>
    
                                                            <div class="col-lg-6">
                                                            <div class="form-group">
                                                                {{ Form::label('email','E-Mail', array('class' => 'labelas')) }} <span class="text-danger">*</span>
                                                                {{ Form::text('email', $user->email, [ 'class' => 'form-control ','placeholder' =>'Email', 'required', 'readonly']) }}<br/>
                                                            </div>
                                                            </div>  

                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('signarure','Signarure', array('class' => 'labelas')) }} <span class="text-danger">*</span>
                                                                    <img alt="image" src="{{url(config('constants.signarureStorage').$user->signarure)}}" />
                                                                    <br />
                                                                    {{ Form::file('file_signarure', null, [ 'class' => 'form-control ']) }}<br/>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                {{ Form::label('signarure','Creater Signarure', array('class' => 'labelas')) }}
                                                                <div class="board" id="custom-board-2" style="width : 350px ; height:300px"></div>
                                                                <input type="hidden" name="image_signarure" value="">
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                            <button type="submit" class="btn btn-primary">Edit profile</button>
                                                            </div>
                                                        </div>
                                                    
                                                    </div>
                                                    
                                                    
                                                    
                                                    <div class="col-lg-3 col-xl-3 border-left">
                                                        <b>Register date: </b>: {{$user->created_at}}
                                                        
                                                        <div class="m-b-10"></div>
                                                        
                                                        <div id="avatar_image">
                                                            <img alt="image" src="{{url(config('constants.avantarStorage').'thumbnail/'.$user->avantar)}}" />
                                                            <br />
                                                                        
                                                        </div>  
                                                        <div id="image_deleted_text"></div>                      
    
                                                        
                                                        <div class="m-b-10"></div>
                                                        
                                                        <div class="form-group">
                                                        <label>Change avatar</label> 
                                                        {{ Form::file('file_avantar', null, [ 'class' => 'form-control ']) }}<br/>
                                                        </div>
                                                        
                                                    </div>
                                                    </div>								
                                                    
                                                </form>										
                                                    
                                    </div>	
                                    <!-- end card-body -->								
                                        
                                </div>
                                <!-- end card -->					
    
                            </div>
                            <!-- end col -->	                                  
                    </div>
                    <!-- end row -->	
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')

<script src="{{ asset('js/tinymce.js?vision=') .$vision }}"></script>
<script src="{{ asset('js/drawingboard.js?vision=') .$vision }}"></script>
<script type="text/javascript">
var customBoard2 = new DrawingBoard.Board('custom-board-2', {
	controls: [
		'Color',
		{ Size: { type: 'dropdown' } },
		{ DrawingMode: { filler: false } },
		'Navigation',
		'Download'
	],
	size: 1,
	webStorage: 'session',
	enlargeYourContainer: true
});
$('#drawing-form').on('submit', function(e) {
    //get drawingboard content
    var img = customBoard2.getImg();

    //we keep drawingboard content only if it's not the 'blank canvas'
    var imgInput = (customBoard2.blankCanvas == img) ? '' : img;

    //put the drawingboard content in the form field to send it to the server
    $(this).find('input[name=image_signarure]').val( imgInput );

    //we can also assume that everything goes well server-side
    //and directly clear webstorage here so that the drawing isn't shown again after form submission
    //but the best would be to do when the server answers that everything went well
    customBoard2.clearWebStorage();
});
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
@endsection
