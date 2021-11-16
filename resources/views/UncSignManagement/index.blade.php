@extends('layouts.admin.master')
@section('title', __('message.product'))
@section('stylesheets')
    <link href="{{ asset('css/condition_advance.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
@include('layouts.admin.breadcrumb_index', [
    'title'       => __('message.product'),
    'page_name'   => __('message.product'),
])
<div class="row">
    <div class="col-md-12">
        <a class="btn btn-primary pull-right" href="{{ url('admin/product/create') }}">
            {{ __('message.create')}}
        </a>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-12">
        <form action="{{ url('admin/uncSign') }}" method="GET" class="form-horizontal" >
            <div class="card">
                <div class="card-header">
                    <label  class="font-weight-bold" for="searchmail"> {{ __('message.search')}}</label>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            {{ Form::label('name_en', __('message.name'), ['class' => 'labelas']) }}
                            {{ Form::text('name', $search_params['name'], ['class' => 'form-control']) }}
                        </div>
                        {{-- <div class="col-md-6">
                            {{ Form::label('created_user', __('message.account_create'), ['class' => 'labelas']) }}
                            {{ Form::select('created_user', $admin_list, $search_params['created_user'], ['id' => 'created_user', 'class' => 'select2 form-control', 'placeholder' => ' ']) }}
                            {{ Form::label('created_at', __('message.date_created'), ['class' => 'labelas']) }}
                            {{ Form::text('created_at', $search_params['created_at'], ['class' => 'form-control datepicker']) }}
                        </div>
                        <div class="col-md-6">
                            {{ Form::label('updated_user', __('message.account_edit'), ['class' => 'labelas']) }}
                            {{ Form::select('updated_user', $admin_list, $search_params['updated_user'], ['id' => 'updated_user', 'class' => 'select2 form-control', 'placeholder' => ' ']) }}
                            {{ Form::label('updated_at', __('message.date_updated'), ['class' => 'labelas']) }}
                            {{ Form::text('updated_at', $search_params['updated_at'], ['class' => 'form-control datepicker']) }}
                        </div> --}}
                    </div>
                    <br>
                    <button type="submit" class="btn btn-info">{{ __('message.search') }}</button>
                    <button type="button" id="clearForm" class="btn btn-default">{{ __('message.reset') }}</button>
                </div>
            </div>
            @include('layouts.admin.partials.condition_advance', ['limit_list' => $limit_list, 'limit' => $limit, 'list' => $data, 'urlPost' => route('product.index'), 'search_params' => $search_params])
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                @if (count($data) > 0)
                {{ $data->appends($search_params)->links() }}
                @endif
            </div>
            <div class="card-body">
                @if (count($data) > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <!-- Table Headings -->
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Action</th>
                                <th>Unc non Sign</th>
                                <th>Unc Signed</th>
                                <th>{{ __('message.date_created')}}</th>
                                <th>{{ __('message.date_updated')}}</th>
                                
                            </tr>
                        </thead>
                        <!-- Table Body -->
                        @foreach ($data as $value)
                        <tbody>
                            <tr>
                                <!-- ticket info -->
                                <td>{{ $value->name }}</td>
                                <td>{{ $value->status == 0 ? "Waiting Sign" : "Signed" }}</td>
                                <td>
                                    @if ($value->status == 0)
                                    @hasanyrole('Header|Manager|Admin')
                                    {{ Form::open(array('url' => "admin/uncSign/{$value->id}", 'method' => 'post' ,'class'=>'form-horizontal')) }}
                                    @method('PUT')
                                        <button>Sign</button>
                                    {{ Form::close() }}
                                    @endhasanyrole   
                                    @endif
                                </td>
                                <td>
                                    @if ($value->url_non_sign) 
                                        <button data-file="{{$value->url_non_sign}}" onclick="viewfile(this);"><i class="fa fa-eye" aria-hidden="true"></i></button>
                                    @endif
                                </td>
                                <td>
                                    @if ($value->url_signed) 
                                        <button data-file="{{$value->url_signed}}" onclick="viewfile(this);"><i class="fa fa-eye" aria-hidden="true"></i></button>
                                    @endif
                                </td>
                                <td>{{ $value->created_at }}</td>
                                <td>{{ $value->updated_at }}</td>
                                
                            </tr>
                        </tbody>
                        @endforeach
                    </table>
                </div>
                {{ $data->appends($search_params)->links() }}
                @endif
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="unc_file" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">View File</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <embed  id='link_file' src="" width="780" height="500" type="application/pdf">
        
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
    </div>
</div>
</div>
    <!-- End Modal -->

@include('layouts.admin.partials.delete_model', [
    'title'           => __('message.product_warning'),
    'confirm_message' => __('message.product_confirm'),
])

@endsection
@section('scripts')
<script src="{{asset('js/lengthchange.js?vision=') .$vision }}"></script>
<script>
    function viewfile(e) {
    var dir = "{{ asset('storage/unc_sign') }}/";
    var url = e.dataset.file;
    $("#link_file").attr("src",dir+url);
    var parent = $('#link_file').parent();
    var newElement = "<embed src='"+dir+url+"' id='link_file' width='100%' height='500' type='application/pdf'>";
    $('#link_file').remove();
    parent.append(newElement);
    $( '#unc_file' ).modal( 'toggle' );
}
</script>
@endsection
