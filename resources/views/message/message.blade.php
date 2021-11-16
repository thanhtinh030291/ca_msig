@if(count($errors)>0)
    @foreach($errors->all() as $error)
    <div class="alert alert-danger mt-2">
        {{$error}}
    </div>
    @endforeach
@endif

@if(Session::has('status'))
    <div class="alert alert-success mt-2"><span class="glyphicon glyphicon"></span><em> {!! session('status') !!}</em></div>
@endif

@if(Session::has('errorStatus'))
    <div class="alert alert-danger mt-2"><span class="glyphicon glyphicon"></span><em> {!! session('errorStatus') !!}</em></div>
@endif