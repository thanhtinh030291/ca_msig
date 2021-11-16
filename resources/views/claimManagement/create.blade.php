
@extends('layouts.admin.master')
@section('title', __('message.claim_create'))
@section('stylesheets')
    <link href="{{asset('css/fileinput.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
    <link href="{{asset('css/formclaim.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/icheck.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
@include('layouts.admin.breadcrumb_index', [
    'title'       => __('message.claim_create'),
    'parent_url'  => route('claim.index'),
    'parent_name' => __('message.claim_management'),
    'page_name'   => __('message.claim_create'),
])

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                {{ Form::open(array('files' => true,'url' => 'admin/claim', 'method' => 'post' ,'class'=>'form-horizontal')) }}
                <!-- Add file file -->
                <div class="row">
                    <div class="col-md-3">
                        {{ Form::label('file', 'File ORC', array('class' => 'labelas')) }} <span class="text-danger">*(CSV )</span>
                        {{ Form::file('file', array('id' => "fileUpload", 'class' => "file")) }} 
                        <button type="button" class="btn btn-danger mt-2 float-right" onclick="btnScan()" ><i class="fa fa-print" aria-hidden="true"></i> Scan</button>
                    </div>
                    <div class="col-md-3">
                        {{ Form::label('_url_file_sorted', 'Tệp đã được sắp sếp', array('class' => 'labelas')) }} <span class="text-danger">*(PDF)</span>
                        {{ Form::file('_url_file_sorted[]', array('id' => "fileUpload2", 'class' => "file")) }} 
                    </div>
                    <div class="col-md-6">
                        {{ Form::label('code_claim', __('message.code_claim'), array('class' => 'labelas')) }} <span class="text-danger">*</span>
                        {{ Form::select('code_claim', [], old('code_claim'), array('id'=>'code_claim', 'class' => 'code_claim form-control', 'required')) }}
                        <div class="card">
                            <h5 class="card-header">Applicant Information</h5>
                            <div class="card-body" id="result_applicant">
                                
                            </div>
                        </div>
                        {{ Form::label('member_name', 'Member Name', array('class' => 'labelas')) }}
                        {{ Form::text('member_name', old('member_name'), array('class' => 'member_name form-control')) }}
                        {{ Form::label('code_claim_show', 'Code Claim', array('class' => 'labelas')) }}
                        {{ Form::text('code_claim_show', old('code_claim_show'), array('class' => 'code_claim_show form-control')) }}
                        {{ Form::label('barcode', 'Barcode', array('class' => 'labelas')) }}
                        {{ Form::text('barcode', old('barcode'), array('id'=>'barcode', 'class' => 'barcode form-control', 'required')) }}
                        <div id="page">
                            <div id="list-page"></div>
                            <div id="show-page"></div>
                        </div>
                        <button type="button" class=" btn btn-success button-preview"><i class="fa fa-search-plus" aria-hidden="true"></i></button>
                    </div>
                </div>
                <div class="row mt-5">
                    <button type="button" class=" btn btn-success" onclick="shortenTable()">Shorten the table</button>
                    <div class="table-responsive" id="dvExcel" style="max-height:450px" >
                    </div>
                </div>
                <div class="row p-2 mt-3 .bg-light">
                    <div class="form-check col-md-1">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input resize-checkbox" value="" onClick="checkAll(this)" > 
                                <p class="ml-2 mt-2">Check All</p>
                            </label>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div  class='col-md-11'>
                                {{ Form::select('_sel', $listReasonReject, old('_sel'), array( 'id'=>'select-inject-default','class' => 'select2 labelas')) }}
                            </div>
                            <button type="button" onclick="clickGo()" class="btn btn-secondar col-md-1">GO</button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row d-flex justify-content-end">
                            <button type="button" onclick="totalAmount()" class="btn btn-secondar col-md-3">Total Amount</button>
                            <p id="totalAmount" class="col-md-4 bg-danger p-2 m-0 font-weight-bold text-white"></p>
                        </div>
                    </div>
                </div>
                <!-- add new table -->
                <div class="row mt-5">
                    <div class="col-sm-2 col-form-label ">
                        {{ Form::label('new_items_reject', __('message.new_items_reject'), array('class' => 'card-title')) }}
                    </div>
                    <div class="col-sm-9">
                        <button type="button" class="btn btn-secondary mt-2 btnt" onclick="addInputItem()">{{ __('message.add')}}</button>
                    </div> 
                </div>
                <div class="row ">
                    <div class="card table-responsive col-md-9"  style="max-height:450px">
                        @include('layouts.admin.form_reject', [
                            'listReasonReject'   => $listReasonReject,
                        ])
                    </div>
                    <div class="card col-md-3">
                        <div class="card-body" style="overflow: scroll; height: 350px;"> 
                            <h5 class="card-title">Do it quickly </h5>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input resize-checkbox" value="" onClick="checkAll2(this)" > 
                                    <p class="ml-2 mt-2">Check All</p>
                                </label>
                                
                            </div>
                            {{ Form::select(null, $listReasonReject,null, array( 'id'=>'select-inject-default2','class' => 'select2 labelas', 'onchange' => 'template_clone()')) }}
                            <div id="result_reason_reject">

                            </div>
                            <button type="button" onclick="clickGo2()" class="btn btn-secondar">GO</button>
                        </div>
                    </div>
                </div>
                <!-- end add new table -->
                <br/>
                <div class="d-flex justify-content-center">
                    <a class="btn btn-secondary btnt" href="{{url('admin/claim')}}">
                        {{ __('message.back')}}
                    </a>
                    <button type="submit" class="btn btn-danger center-block btnt ml-3" > {{__('message.save')}}</button>
                </div>
                {{ Form::close() }}
                
                
            </div>
        </div>
    </div>
</div>
<!-- clone  -->
<div id='clone-select-reject' style = "display:block">
    {{ Form::select('_selectReason_default', $listReasonReject, old('_selectReason'), [ 'id' => 'selectReason_default', 'class' => 'form-control' , 'style' => 'width:230px', 'placeholder' => 'Not Reject', 'data-id' => "data-id-default" , 'onchange' => 'template(this,"resuft_template_default","table1")']) }}        
</div>
@endsection
@section('scripts')
    <script src="{{asset('js/fileinput.js?vision=') .$vision }}"></script>
    <script src="{{asset('js/papaparse.min.js?vision=') .$vision }}"></script>
    <script src="{{asset('js/popper.min.js?vision=') .$vision }}" ></script>
    <script src="{{ asset('js/tiff.min.js?vision=') .$vision }}"></script>
    <script src="{{ asset('js/format-price.js?vision=') .$vision }}"></script>
    <script src="{{ asset('js/jquery-ui.js?vision=') .$vision }}"></script>
    <script src="{{ asset('js/typeahead.bundle.min.js')}}"></script>
    <script src="{{ asset('js/clipboard.js?vision=') .$vision }}"></script>
    <script src="{{asset('js/formclaim.js?vision=') .$vision }}"></script>
    <script src="{{ asset('js/icheck.min.js?vision=') .$vision }}"></script>
    
    <script type="text/javascript">
        function arrayToTable(tableData) {
            var table = $('<table class="table table-striped header-fixed"></table>');
            //option select field
            var arrOption = @json(config('constants.field_select'));
            var selectOption = '<select name = "_column[]" class="form-control select_field">';
            selectOption += '<option value="" selected >---X---</option>';
            $.each(arrOption, function (index, value) {
                selectOption += '<option value="'+index+'" >'+value+'</option>';
            });
            selectOption += '</select>';

            $(tableData).each(function (i, rowData) {
                var row = $('<tr></tr>');
                if(i == 0){
                    row.append("<th></th>")
                        .append('<th>Reason Reject</th>')
                        .append('<th>Template</th>')

                }else{
                    row.append($('<td class="row pl-0 pr-0 m-0" style = "width : 80px"></td>')
                        .append($('<input class = "checkbox_class col-md-6 resize-checkbox" type="checkbox"  data-id = "'+i+'" />'))
                        .append($('<button type="button" class=" col-md-6 delete_row_btn btn p-0 btn-danger" data-toggle="tooltip" title="Delete this row in the table!" >&#x2613;</button>'))
                    );
                    var cloneSelect = $("#clone-select-reject").clone().html();
                    cloneSelect = cloneSelect.replace('resuft_template_default', "resuft_template_"+i);
                    cloneSelect = cloneSelect.replace('data-id-default', i);
                    cloneSelect = cloneSelect.replace('selectReason_default', "selectReason_" + i); // change id
                    cloneSelect = cloneSelect.replace('_selectReason_default', "_selectReason["+i+"]"); // change name
                    
                    
                    row.append(
                        $('<td></td>')
                            .append($("<div style='width:250px'></div>").append($(cloneSelect).addClass('select2')))
                    );
                    row.append(
                        $('<td><div style="max-width: 380px" id="resuft_template_'+i+'"></div></td>')
                    );
                }
                $(rowData).each(function (j, cellData) {
                    cellData = cellData  ? cellData : ""; 
                    if(i != 0){
                        row.append($('<td><input class ="form-control" name = "_row['+i+'][]" data-id= "'+i+'" value = "'+cellData+'" /></td>'));
                    }else{
                        row.append($('<th></th>')
                            .append($(selectOption).attr('id', j))
                            .append($('<input name = "_row['+i+'][]" data-id= "'+j+'" value = "'+cellData+'" class ="p-1 bg-info" readonly />'))
                        );
                    }
                    
                });
                table.append(row);
            });
            
            return table;
        } 
    </script>
    <script>
        // add value select to row and change tooltip
        
        $(document).ready(function(){
            type_ahead();
            icheck_fn();
            $('[data-toggle="tooltip"]').tooltip({
                customClass: 'tooltip-custom'
            });
            //add old value
            var old_data_row = @json(old('_row'));
            if(old_data_row != null){
                $('#dvExcel').append(arrayToTable(Object.values(old_data_row)));
            }
            var old_data_column = @json(old('_column'));
            if(old_data_row != null){
                $.each(old_data_column, function (index, value) {
                    $('#'+index).val(value).change();
                });
            }
            
        });

    </script>
    
    <script>
        // old 
        $(document).on('ready', function() {
            var content = @json(old('_content'));
            var amount = @json(old('_amount'));
            var reasonInject = @json(old('_reasonInject'));
            if(content != null){
                $.each(content, function (index, value) {
                    addInputItem();
                    addValueItem(content[index],amount[index],reasonInject[index],count-1);
                });
            }
            var table2_parameters = @json(old('table2_parameters')? array_values(old('table2_parameters')) : old('table2_parameters')) ;
            if(table2_parameters != null){
                setTimeout(function(){
                    $.each(table2_parameters, function (index, value) {
                        var i = parseInt(index)+1;
                        var el = $('input[name="table2_parameters['+i+'][]"]');
                        $.each(el, function (index2, value2) {
                            $(this).val(value[index2]);
                        });
                    });
                },200);
            }
        });
    </script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
    </script>
@endsection
