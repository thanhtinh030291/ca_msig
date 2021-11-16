@extends('layouts.admin.master')
@section('title', 'Provider')
@section('stylesheets')
<link rel="stylesheet" type="text/css" href="{{asset('css/jquery-ui.min.css')}}">
@endsection
@section('content')
    @include('layouts.admin.breadcrumb_index', [
        'title'       => 'Provider',
        'parent_url'  => route('providers.index'),
        'parent_name' => 'Providers',
        'page_name'   =>  'Provider',
    ])
   <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                   {!! Form::model($provider, ['route' => ['providers.update', $provider->id], 'method' => 'patch']) !!}

                        @include('providers.fields')

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
