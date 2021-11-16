<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="{{ config('app.name') }}">
    <meta name="author" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name='key-notify' content="{{ config("constants.VAPID_PUBLIC_KEY") }}">
    <meta name="ws_url" content="http://localhost:3000/">
    <meta name="user_id" content="{{ Auth::id() }}">
    <meta name="user_name" content="{{Auth::user()->name}}">
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}">

    <!-- Bootstrap CSS -->
    <link href="{{asset('css/bootstrap.min.css?vision=') .$vision }}" rel="stylesheet" type="text/css" />

    <!-- Font Awesome CSS -->
    <link href="{{asset('font-awesome/css/font-awesome.min.css?vision=') .$vision }}" rel="stylesheet" type="text/css" />

    <!-- Custom CSS -->
    <link href="{{asset('css/style.css?vision=') .$vision }}" rel="stylesheet" type="text/css" />

	<!-- Date range picker CSS -->
	<link href="{{asset('plugins/datetimepicker/css/daterangepicker.css?vision=') .$vision }}" rel="stylesheet" />
    <link href="{{asset('css/select2.min.css?vision=') .$vision }}" rel="stylesheet" type="text/css" />
    <link href="{{asset('css/select2_optimize.css?vision=') .$vision }}" rel="stylesheet" type="text/css" />
    <link href="{{asset('css/bootstrap-notifications.css?vision=') .$vision }}" rel="stylesheet" type="text/css" />
    
    @yield('stylesheets')

</head>

<body class="adminbody">
    <div class="loader"></div>
    <div id="main">
        <!-- top bar navigation -->
        @include('layouts.admin.partials.top_bar_navigation',[
            'messages' => $messages,
            
        ])
        <!-- End Navigation -->
        <!-- Left Sidebar -->
        @include('layouts.admin.partials.left_sidebar')
        <!-- End Sidebar -->
        <div class="content-page">
            <!-- Start content -->
            <div class="content">
			    <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-12">
                            @include('message.message')
                        </div>
                    </div>
                    @yield('content')
                </div>
            </div>
            <!-- END content -->
        </div>
        <!-- END content-page -->
        <!-- Start footer -->
        @include('layouts.admin.partials.footer')
        <!-- END footer -->
    </div>
    <!-- END main -->
    <script src="{{asset('js/jquery-3.3.1.slim.min.js?vision=') .$vision }}"></script>
    <script src="{{asset('js/modernizr.min.js?vision=') .$vision }}"></script>
    <script src="{{asset('js/jquery.min.js?vision=') .$vision }}"></script>
    <script src="{{asset('js/popper.min.js?vision=') .$vision }}"></script>
    <script src="{{asset('js/bootstrap.min.js?vision=') .$vision }}"></script>
    <script src="{{asset('js/detect.js?vision=') .$vision }}"></script>
    <script src="{{asset('js/fastclick.js?vision=') .$vision }}"></script>
    <script src="{{asset('js/jquery.blockUI.js?vision=') .$vision }}"></script>
    <script src="{{asset('js/jquery.nicescroll.js?vision=') .$vision }}"></script>
    <script src="{{asset('js/moment.min.js?vision=') .$vision }}"></script>
    <script src="{{asset('js/daterangepicker.js?vision=') .$vision }}"></script>
    <script src="{{asset('js/jquery.cookie.js?vision=') .$vision }}"></script>
    <script src="{{ asset('js/axios.min.js?vision=') .$vision }}"></script>
    <script src="{{ asset('plugins/tinymce/tinymce.min.js?vision=') .$vision }}"></script>
    <!-- App js -->
    <script src="{{asset('js/pikeadmin.js?vision=') .$vision }}"></script>
    <script src="{{asset('js/pusher.min.js?vision=') .$vision }}"></script>
    <script src="{{ asset('js/select2.min.js?vision=') .$vision }}"></script>
    <script src="{{ asset('js/bootstrap-notify.min.js?vision=') .$vision }}"></script>
    <script src="https://js.pusher.com/5.1/pusher.min.js"></script>
    <script src="{{asset('js/main.js?2')}}"></script>
    
    
    
    
    <script>
        $(window).on("load", function() {
            $(".loader").fadeOut("slow");
        });
        $(document).ready(function(){
            $('.select2').select2();
            //pusher("{{config('broadcasting.connections.pusher.key')}}","{{config('broadcasting.connections.pusher.options.cluster')}}", "{{ Auth::user()->id }}");
        });
        var notificationsWrapper   = $('.dropdown-notifications');
        var notificationsToggle    = notificationsWrapper.find('a[data-toggle]');
        var notificationsCountElem = notificationsToggle.find('i[data-count]');
        var notificationsCount     = parseInt(notificationsCountElem.data('count'));
        var notifications          = notificationsWrapper.find('ul.dropdown-menu');


        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('{{config("constants.PUSHER_APP_KEY")}}', {
            cluster: '{{config("constants.PUSHER_APP_CLUSTER")}}',
            encrypted: true
        });

        // Subscribe to the channel we specified in our Laravel Event
        var channel = pusher.subscribe('NotifyUser-{{ Auth::user()->id }}');
        channel.bind('Notify', function(data) {
            pusher_res(data);
        });

        //Bind a function to a Event (the full Laravel class)

    function sendNotification(){
        var data = new FormData();
        data.append('title', document.getElementById('title').value);
        data.append('body', document.getElementById('body').value);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', "{{url('/api/send-notification/'.auth()->user()->id)}}", true);
        xhr.onload = function () {
        // do something to response
        console.log(this.responseText);
        };
        xhr.send(data);
    }


    </script>
    <!-- BEGIN Java Script for this page -->
    @yield('scripts')
    <!-- END Java Script for this page -->
</body>
</html>
