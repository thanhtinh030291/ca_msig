@extends('layouts.admin.master')
@section('content')
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="breadcrumb-holder">
            <h1 class="main-title float-left">{{ __('message.update')}}</h1>
            <ol class="breadcrumb float-right">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="{{ url('admin/mobileuser')}}">{{
                __('message.mobile_user')}}</a></li>
                <li class="breadcrumb-item active">{{ __('message.update')}}</li>
            </ol>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                {{-- {{ Form::open(array('url' => '/admin/user/'.$data->id, 'method'=>'post', 'id' => 'frmUpdateStaff', 'files' => true))}} @method('PUT') --}}
                    <!-- Staff info -->
                <div class="row">
                    <div class="col-md-6 p-2">
                        {{ Form::label('name',__('message.name'), array('class' => 'labelas')) }}
                        {{ Form::text('fullname', $data->fullname, ['class' => 'form-control','placeholder'=>__('message.enter_please'),  'required', 'readonly']) }}<br>

                        {{ Form::label('email',__('message.email'), array('class' => 'labelas')) }}
                        {{ Form::text('email', $data->email, ['class' => 'form-control','placeholder'=>__('message.enter_please'), 'required', 'readonly']) }}<br>

                        {{ Form::label('mr_no',__('message.mbr_no'), array('class' => 'labelas')) }}
                        {{ Form::text('mbr_no', $data->mbr_no, ['class' => 'form-control','placeholder'=>__('message.enter_please'), 'required', 'readonly']) }}<br>

                        {{ Form::label('mr_no',__('message.pocy_no'), array('class' => 'labelas')) }}
                        {{ Form::text('pocy_no', $data->pocy_no, ['class' => 'form-control','placeholder'=>__('message.enter_please'), 'required', 'readonly']) }}<br>

                        {{ Form::label('company',__('message.company'), array('class' => 'labelas')) }}
                        {{ Form::text('company', $data->company, ['class' => 'form-control','placeholder'=>__('message.enter_please'),   'readonly']) }}<br>
                    </div>
                    <div class="col-md-6 p-2">
                        

                        {{ Form::label('tel',__('message.tel'), array('class' => 'labelas')) }}
                        {{ Form::text('tel', $data->tel, ['class' => 'form-control','placeholder'=>__('message.enter_please'), 'readonly']) }}<br>

                        {{ Form::label('id_card',__('message.id_card'), array('class' => 'labelas')) }}
                        {{ Form::text('id_card', $data->id_card, ['class' => 'form-control','placeholder'=>__('message.enter_please'), 'readonly']) }}<br>

                        {{ Form::label('first_login',__('message.first_login'), array('class' => 'labelas')) }}
                        {{ Form::text('first_login', $data->first_login, ['class' => 'form-control','placeholder'=>__('message.enter_please'), 'readonly']) }}<br>

                        {{ Form::label('id_card',__('message.last_login'), array('class' => 'labelas')) }}
                        {{ Form::text('last_login', $data->last_login, ['class' => 'form-control','placeholder'=>__('message.enter_please'), 'readonly']) }}<br>

                        {{ Form::label('id_card',__('message.resrouce'), array('class' => 'labelas')) }}
                        {{ Form::text('resoure', $data->resoure, ['class' => 'form-control','placeholder'=>__('message.enter_please'), 'readonly']) }}<br>



                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 p-2">
                        {{ Form::label('front_card_url',__('message.front_card_url'), array('class' => 'labelas')) }}<br>
                        <img src="{{loadImg($data->front_card_url, asset(config('constants.photoStorage')))}}" alt="img" class="img-thumbnail" width="350" height="236"/><br>
                    </div>
                    <div class="col-md-6 p-2">
                        {{ Form::label('back_card_url',__('message.back_card_url'), array('class' => 'labelas')) }}<br>
                        <img src="{{loadImg($data->back_card_url, asset(config('constants.photoStorage')))}}" alt="img" class="img-thumbnail" width="350" height="236"/><br>
                    </div>
                </div>
                <div class="row">
                    <div class="card col-md-6 p-2 mt-2">
                        {{ Form::open(array('url' => '/admin/notification/'.$data->id, 'method'=>'post', 'id' => 'frmUpdateStaff', 'files' => true))}}
                            <h5 class="card-header">Notication</h5>
                            <div class="card-body">
                                <h5 class="card-title">Title</h5>
                                <input type="text" name="title" class="card-text form-control" required />
                                <h5 class="card-title">Contents</h5>
                                <textarea name="contents" class="card-text form-control"></textarea>
                                <button type="submit" class="btn btn-primary">Send</button>
                            </div>
                        {{ Form::close() }}
                    </div>

                    <div class="card col-md-6 p-2 mt-2">
                        <table class="table">
                            <thead>
                              <tr>
                                <th scope="col">#</th>
                                <th scope="col">platform</th>
                                <th scope="col">token</th>
                                <th scope="col">time accept</th>
                              </tr>
                            </thead>
                            <tbody>
                            @foreach ($data->mobile_device as $k => $value)
                                <tr>
                                <th scope="row">{{ $k+1 }}</th>
                                <td>{{$value->device_type}}</td>
                                <td>{{ truncate($value->device_token ,10) . "..."}}</td>
                                <td>{{ $value->updated_at }}</td>
                              </tr>
                            @endforeach
                            </tbody>
                          </table>
                    </div>
                </div>

                    <!-- Add update Button -->
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            <a class="btn btn-secondary" href="{{url('admin/mobileuser')}}"> {{ __('message.back')}} </a>
                            {{-- {{ Form::submit( __('message.save'),['class' => 'btn btn-primary center-block']) }}<br> --}}
                        </div>
                    </div>
                {{-- {{ Form::close() }} --}}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

@endsection
