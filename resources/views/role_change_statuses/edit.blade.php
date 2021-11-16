@extends('layouts.admin.master')
@section('title', 'Role Change Status')
@section('stylesheets')
<link rel="stylesheet" type="text/css" href="{{asset('css/jquery-ui.min.css?vision=') .$vision }}">
@endsection
@section('content')
    @include('layouts.admin.breadcrumb_index', [
        'title'       => 'Role Change Status',
        'parent_url'  => route('roleChangeStatuses.index'),
        'parent_name' => 'Role Change Statuses',
        'page_name'   =>  'Role Change Status',
    ])
   <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                   {!! Form::model($roleChangeStatus, ['route' => ['roleChangeStatuses.update', $roleChangeStatus->id], 'method' => 'patch']) !!}

                        @include('role_change_statuses.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection
@section('scripts')
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
@endsection
