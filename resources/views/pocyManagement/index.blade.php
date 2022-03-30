@extends('layouts.admin.master')
@section('title', 'Policy Management')
@section('stylesheets')
<link href="{{ asset('css/condition_advance.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet"/>
<link href="{{ asset('css/fontawesome.min.css') }}" rel="stylesheet"/>
<link href="{{ asset('css/datatables.min.css') }}" rel="stylesheet"/>
<link href="{{ asset('css/jquery-ui.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/tagsinput.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
<div class="row my-3">
    <div class="col-12">
        <div class="card border-info">
            <div class="card-header text-info">
                <div class="row">
                    <div class="col-12">
                        <h3>{{ __('message.search')}}</h3>
                    </div>
                </div>
                <form action="{{ url('admin/pocy') }}" method="POST" class="was-validated form-horizontal card-body">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-4">
                            {{ Form::label('pocy_ref_no', "Policy Ref No", ['class' => 'col-form-label text-center']) }}
                            {{ Form::select('pocy_ref_no', $list_pocy,null, ['class' => ' select2 form-control']) }}
                        </div>
                        
                        <div class="form-group col-8">                            
                            {{ Form::label('_list_provider[]', 'Provider Not GOP', ['class' => 'col-form-label text-center']) }}
                            {{ Form::select('_list_provider[]', $list_provider, null, ['id'=>'list_provider','class' => 'select2 form-control' ,'multiple']) }}
                        </div>
                        <div class="form-group col-12">                            
                            {{ Form::label('_list_provider[]', 'Email Customers', ['class' => 'col-form-label text-center']) }}
                            {{ Form::text('email', null, [ 'class' => 'form-control','placeholder' =>'Email',  'data-role' => 'tagsinput']) }}<br/>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-danger"> {{ __('message.add') }}</button>
                </form>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr class="bg-info text-white">
                            <th scope="col">{{ __('message.id') }}</th>
                            <th scope="col">Pocy Ref No</th>
                            <th scope="col">Emails</th>
                            <th class='text-center control_btn'>{{ __('message.edit') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $row)
                        <tr>
                            <th scope="col" class="text-right">{{ $row->id }}</th>
                            <td scope="col">{{ $row->pocy_ref_no }}</td>
                            <td scope="col">{{ $row->email }}</td>
                            <td class='text-center'>
                                <!-- control -->
                                <a class="btn btn-success" href='{{ url("admin/pocy/$row->id/edit") }}'>{{ __('message.edit') }}</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript" src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/datatables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/tagsinput.js?vision=') .$vision }}"></script>
<script>
$( document ).ready( function() {
    
    var table = $( 'table' ).DataTable( {
        lengthChange: false,
        order: [[ 0, "asc" ]],
        fixedHeader: true,
        orderCellsTop: true,
    } );
    
} );
</script>
@endsection
