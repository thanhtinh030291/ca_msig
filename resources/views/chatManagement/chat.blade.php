
@extends('layouts.admin.master')
@section('title', __('message.chat'))
@section('stylesheets')
<meta name="ws_url" content="http://localhost:3000/">
<meta name="user_id" content="{{ Auth::id() }}">

<link href="{{ asset('css/app.css?vision=') .$vision }}" rel="stylesheet">
<link href="{{ asset('css/chat.css?vision=') .$vision }}" rel="stylesheet">
<style media="screen">
    .online{
        color: #32CD32;
    }
</style>
@endsection
@section('content')
@include('layouts.admin.breadcrumb_index', [
    'title'       => __('message.chat'),
    'parent_url'  => route('chat.index'),
    'parent_name' => __('message.chat'),
    'page_name'   => __('message.chat'),
])
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="row">
                <div class="col-md-3" id="chatApp">
                    <div class="ffside">
                        <div class="">Users</div>
                        <div class="" style="padding:0px;">
                            <ul class="list-group">
                                <li class="list-group-item" v-for="chatList in chatLists" style="cursor: pointer;" @click="chat(chatList)">@{{ chatList.name }}  <i class="fa fa-circle pull-right" v-bind:class="{'online': (chatList.online=='Y')}"></i>  <span class="badge" >@{{ chatList.msgCount == 0 ? "" : chatList.msgCount }}</span></li>
                                <li class="list-group-item" v-if="socketConnected.status == false">@{{ socketConnected.msg }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-9" id="app">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/vue.js?vision=') .$vision }}"></script>
<script src="{{ asset('js/socket.io.js?vision=') .$vision }}"></script>
<script src="{{ asset('js/chat.js?vision=') .$vision }}" charset="utf-8"></script>
@endsection
