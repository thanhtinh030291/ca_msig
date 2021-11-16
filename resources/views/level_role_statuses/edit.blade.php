@extends('layouts.admin.master')
@section('title', 'Level Role Status')
@section('stylesheets')
<link rel="stylesheet" type="text/css" href="{{asset('css/jquery-ui.min.css?vision=') .$vision }}">
@endsection
@section('content')
    @include('layouts.admin.breadcrumb_index', [
        'title'       => 'Level Role Status',
        'parent_url'  => route('levelRoleStatuses.index'),
        'parent_name' => 'Level Role Statuses',
        'page_name'   =>  'Level Role Status',
    ])
   <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                   {!! Form::model($levelRoleStatus, ['route' => ['levelRoleStatuses.update', $levelRoleStatus->id], 'method' => 'patch']) !!}

                        @include('level_role_statuses.fields')

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
