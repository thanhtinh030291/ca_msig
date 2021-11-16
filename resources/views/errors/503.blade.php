@extends('errors::minimal')

@section('title', __('Service Unavailable'))
@section('code', '503')
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body style="text-align:center;">
    <h1 style="margin:auto; color:#000; margin-top:30px;">
        {{ $exception->getMessage() }}
    </h1>
    <h1 style="margin:auto; color:#000;">Vui lòng quay lại sau !</h1>
    <img src="{{url('images/bt.png')}}">
</body>
</html>

