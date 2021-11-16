@extends('layouts.admin.master')
@section('title', 'Group User')
@section('stylesheets')
<link rel="stylesheet" type="text/css" href="{{asset('css/jquery-ui.min.css')}}">
@endsection
@section('content')
    @include('layouts.admin.breadcrumb_index', [
        'title'       => 'Group User',
        'parent_url'  => route('groupUsers.index'),
        'parent_name' => 'Group Users',
        'page_name'   =>  'Group User',
    ])
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    {!! Form::open(['route' => 'groupUsers.store']) !!}
                        @include('group_users.fields')
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{asset('js/jquery-ui.min.js?vision=') .$vision }}"></script>
<script type="text/javascript">
    var list_gr = [];
    $(function() {
        $( ".sortable_list" ).sortable({
            connectWith: ".connectedSortable",
            receive: function(event, ui) {
                //alear("dropped on = "+this.id); // Where the item is dropped
                //("sender = "+ui.sender[0].id); // Where it came from
                //alert("item = "+ ui.item[0].id); //Which item (or ui.item[0].id)
                list_gr = $( "#sortable1" ).sortable("toArray");
                $("#group_user").val(list_gr.toString());
                console.log(list_gr);
            }         
        }).disableSelection();
    });
</script>
@endsection
