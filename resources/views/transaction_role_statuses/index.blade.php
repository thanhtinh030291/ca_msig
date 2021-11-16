@extends('layouts.admin.master')
@section('title', 'Transaction Role Statuses')
@section('stylesheets')
    <link href="{{ asset('css/condition_advance.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    @include('layouts.admin.breadcrumb_index', [
        'title'       => 'Transaction Role Statuses',
        'page_name'   => 'Transaction Role Statuses',
    ])


    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        @include('transaction_role_statuses.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection
@section('scripts')
    <script src="{{asset('js/lengthchange.js?vision=') .$vision }}"></script>
    <script>
        $(document).on("click", ".delete_btn", function(){
            $(this).closest('tr').remove();
        });
    $(".addinput").click(function(){
        var id = $(this).data('id');
        addInputItem(id);
    });
    var count = 1;
    function addInputItem(id){
        let clone =  '<tr id="row-'+count+'">';
        clone += '<input name = "id['+count+']" type="hidden" >';
        clone +=  $("#clone_item_"+id).clone().html() + '</tr>';
        //repalace name
        clone = clone.replace("current_status_default", "current_status["+count+"]");
        clone = clone.replace("role_default", "role["+count+"]");
        clone = clone.replace("to_status_default", "to_status["+count+"]");
        // div template id
        $("#empty_item_"+id).before(clone);
        count++;
    }

    function addValueItem(from_status, role, to_status, count, idItem = ""){
        $('select[name="current_status['+count+']"]').val(from_status).change();
        $('select[name="role['+count+']"]').val(role).change();
        $('select[name="to_status['+count+']"]').val(to_status).change();
        $('input[name="id['+count+']"]').val(idItem);
    }
    $(document).on('ready', function() {
        var data = @json($data);
        console.log(data);
        $.each(data, function( index_data, value_data ) {
            var lv_id = value_data.id ;
            $.each(value_data.transaction_role_status, function( index, value ) {
                addInputItem(lv_id);
                addValueItem(value.current_status, value.role, value.to_status, count-1, value.id);
            });
        });

        // var id_get = $("#dataget").data('id');
        // var fromStatus_get  = $("#dataget").data('fromstatus');
        // var role_get = $("#dataget").data('role');
        // var toStatus_get = $("#dataget").data('tostatus');
        
        // if(id_get != null){
        //     $.each(id_get, function (index, value) {
                
        //         addInputItem();
        //         addValueItem(fromStatus_get[index],role_get[index],toStatus_get[index],count-1,id_get[index]);
        //     });
        // }
        
    });
    </script>
@endsection

