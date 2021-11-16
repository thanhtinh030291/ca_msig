@extends('layouts.admin.master')
@section('title', 'Report Admin')
@section('stylesheets')
<link rel="stylesheet" type="text/css" href="{{asset('css/jquery-ui.min.css')}}">
@endsection
@section('content')
    @include('layouts.admin.breadcrumb_index', [
        'title'       => 'Report Admin',
        'parent_url'  => route('reportAdmins.index'),
        'parent_name' => 'Report Admins',
        'page_name'   =>  'Report Admin',
    ])
   <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                   {!! Form::model($reportAdmin, ['route' => ['reportAdmins.update', $reportAdmin->id], 'method' => 'patch']) !!}

                        @include('report_admins.fields')

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
