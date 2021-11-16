<!-- Stored in resources/views/layouts/admin/partials/top_bar_navigation.blade.php -->
@extends('layouts.admin.master')
@section('title', 'PanciFic-Cross-Admin')
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="breadcrumb-holder">
                <h1 class="main-title float-left">Dashboard</h1>
                <ol class="breadcrumb float-right">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    
    <div class="row">
            <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                    <div class="card-box noradius noborder bg-default">
                            <i class="fa fa-file-text-o float-right text-white"></i>
                            <h6 class="text-white text-uppercase m-b-20">claims</h6>
                            <h1 class="m-b-20 text-white counter">{{$sumClaim}}</h1>
                            <span class="text-white">{{$sumClaimToDate}} New Orders To Date</span>
                    </div>
            </div>

            <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                    <div class="card-box noradius noborder bg-warning">
                            <i class="fa fa-bar-chart float-right text-white"></i>
                            <h6 class="text-white text-uppercase m-b-20">IP Address</h6>
                            <h1 class="m-b-20 text-white counter">{{250}}</h1>
                    <span class="text-white">My IP: {{$Ipclient}}</span>
                    </div>
            </div>

            <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                    <div class="card-box noradius noborder bg-info">
                            <i class="fa fa-user-o float-right text-white"></i>
                            <h6 class="text-white text-uppercase m-b-20">Users</h6>
                            <h1 class="m-b-20 text-white counter">{{$sumMember}}</h1>
                            <span class="text-white">{{$sumMember}} New Users</span>
                    </div>
            </div>

            <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                    <div class="card-box noradius noborder bg-danger">
                            <i class="fa fa-bell-o float-right text-white"></i>
                            <h6 class="text-white text-uppercase m-b-20">Alerts</h6>
                            <h1 class="m-b-20 text-white counter">58</h1>
                            <span class="text-white">5 New Alerts</span>
                    </div>
            </div>
    </div>

    <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">						
                <div class="card mb-3">
                    <div class="card-header">
                        <h3><i class="fa fa-users"></i> Send message</h3>
                        Use sent massage to notify someone
                    </div>
                        
                    <div class="card-body">
                            <div class="container">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <form action="{{route('postMessage')}}" method="post">
                                        @csrf
                                        {{ Form::label('user', 'Email', array('class' => 'labelas')) }} <span class="text-danger">*</span>
                                        {{ Form::select('user', $listUser, old('user'), array( 'class' => 'select2 form-control', 'required')) }}
                                        
                                
                                        <label for="subject">Content</label>
                                        <textarea id="content" class="form-control" name="content" placeholder="Write something.." style="height:200px"></textarea>
                                
                                        <input type="submit" class='btn btn-primary' value="Submit">
                                
                                    </form>
                                </div>
                        
                    </div>														
                </div><!-- end card-->					
            </div>

            
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-3">						
                <div class="card mb-3">
                    <div class="card-header">
                        <h3><i class="fa fa-star-o"></i> Message sent</h3>
                        Below in this page, the sent message is presented in a format which is suitable for distribution.
                    </div>
                        
                    <div class="card-body">
                        <div class="widget-messages nicescroll" style="height: 400px;">
                            @foreach($sentMessages as $sentMessage)
                            <a href="#">
                                <div class="message-item">
                                    <div class="message-user-img"><img src="/images/avatars/admin.png" class="avatar-circle" alt=""></div>
                                    <p class="message-item-user">{{isset($sentMessage->userTo->name) ? $sentMessage->userTo->name : " "}}</p>
                                    <p class="message-item-msg">{!!$sentMessage->message!!}</p>
                                    <p class="message-item-date">{{dateConvertToString($sentMessage->created_at)}}</p>
                                </div>
                            </a>
                            @endforeach
                        </div>						
                    </div>
                    <div class="card-footer small text-muted">Updated today at 11:59 PM</div>
                </div><!-- end card-->					
            </div>
    
    
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-3">						
                <div class="card mb-3">
                    <div class="card-header">
                        <h3><i class="fa fa-envelope-o"></i> Latest messages</h3>
                        Below in this page, the latest message is presented in a format which is suitable for distribution.
                    </div>
                        
                    <div class="card-body">
                        
                        <div class="widget-messages nicescroll" style="height: 400px;">
                            @foreach($latestMessages as $latestMessage)
                                <a href="#">
                                    <div class="message-item">
                                        <div class="message-user-img"><img src="/images/avatars/admin.png" class="avatar-circle" alt=""></div>
                                        <p class="message-item-user">{{isset($latestMessage->userFrom->name) ? $latestMessage->userFrom->name : ""}}</p>
                                        <p class="message-item-msg">{!!$latestMessage->message!!}</p>
                                        <p class="message-item-date">{{dateConvertToString($latestMessage->created_at)}}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        
                    </div>
                    <div class="card-footer small text-muted">Updated today at 11:59 PM</div>
                </div><!-- end card-->					
            </div>
            
    </div>
@endsection