@extends('layouts.admin.master')
@section('title', 'Claim Word Sheet')
@section('stylesheets')
<link rel="stylesheet" type="text/css" href="{{asset('css/jquery-ui.min.css')}}">
@endsection
@section('content')
    @include('layouts.admin.breadcrumb_index', [
        'title'       => 'Claim Word Sheet',
        'parent_url'  => route('claimWordSheets.index'),
        'parent_name' => 'Claim Word Sheets',
        'page_name'   =>  'Claim Word Sheet',
    ])
   <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                   {!! Form::model($claimWordSheet, ['route' => ['claimWordSheets.update', $claimWordSheet->id], 'method' => 'patch']) !!}

                        @include('claim_word_sheets.fields')

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
