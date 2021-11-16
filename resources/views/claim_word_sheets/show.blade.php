@extends('layouts.admin.master')
@section('title', 'Claim Word Sheet')
@section('stylesheets')
    <link href="{{ asset('css/fileinput.css') }}" media="all" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-ui.min.css') }}">
    <link href="{{ asset('css/setting_date.css') }}" media="all" rel="stylesheet" type="text/css"/>
    <style>
        .nav-tabs .nav-link {
            border: 1px solid #17a2b8;
            border-top-left-radius: .25rem;
            border-top-right-radius: .25rem;
        }
        .nav-tabs .nav-link:hover {
            background-color: #ecdcbe;
            border-color: #28a745 #28a745 #28a745;
        }
        .nav-tabs .nav-link .active {
            background-color: #ecdcbe;
            border-color: #28a745 #28a745 #28a745;
        }
        table ul li input{
            width: 150px !important;
        }
    </style>

@endsection
@section('content')
    @include('layouts.admin.breadcrumb_index', [
        'title'       => 'Claim Work Sheet',
        'parent_url'  =>  route('claimWordSheets.index'),
        'parent_name' => 'Claim Work Sheets',
        'page_name'   => 'Claim Work Sheet',
    ])
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    {!! Form::model($claimWordSheet, ['route' => ['claimWordSheets.update', $claimWordSheet->id], 'method' => 'patch']) !!}
                    @include('claim_word_sheets.show_fields')

                    <div class="form-group col-sm-12">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        <a href="{!! route('claim.index') !!}" class="btn btn-default">Cancel</a>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    @include('history')
@endsection
@section('scripts')
<script src="{{asset('js/imask.js')}}"></script>
<script src="{{ asset('js/tinymce.js?vision=') .$vision }}"></script>
<script src="{{ asset('js/format-price.js?vision=') .$vision }}"></script>
<script src="{{ asset('js/jsonViewer.js?vision=') .$vision }}"></script>


<script>
//btn delete table item 
$(document).on("click", ".delete_btn", function(){
    $(this).closest('tr').remove();
});

$('.imask-input').mask('AB/SC/MNJK', {'translation': {
        A: {pattern: /[0-3]/},
        B: {pattern: /[0-9]/},
        S: {pattern: /[0-1]/},
        C: {pattern: /[0-9]/},
        M: {pattern: /[0-2]/},
        N: {pattern: /[0-9]/},
        J: {pattern: /[0-9]/},
        K: {pattern: /[0-9]/},
    },
    placeholder: "__/__/____"
});

var count = 1;
function addInputItem(tab_benefit){
    let clone =  '<tr id="row-'+count+'">';
    clone += '<input name = "_idItem['+count+']" type="hidden" >';
    clone += '<input name = "_benefit['+count+']" value="'+tab_benefit+'" type="hidden" >';
    clone +=  $("#clone_item_"+tab_benefit).clone().html() + '</tr>';
    //repalace name
    clone = clone.replace("_content_default", "_content["+count+"]");
    clone = clone.replace("_amount_default", "_amount["+count+"]");
    clone = clone.replace("_reasonInject_default", "_reasonInject["+count+"]");
    // div template id
    clone = clone.replace('template_default', "template_"+count);
    // parameter in function
    clone = clone.replace('nameItem_defautl', "table2_nameItem_"+count);
    clone = clone.replace('amountItem_defautl', "table2_amountItem_"+count);
    clone = clone.replace('template_idElement', "template_"+count);
    // id
    clone = clone.replace('table2_name_default', "table2_name_"+count);
    clone = clone.replace('table2_amount_default', "table2_amount_"+count);
    
    
    $("#clone_item_"+tab_benefit).before(clone);
    $('input[name="_content['+count+']"]').attr({"required": "true", 'data-id': count, 'id': 'table2_name_'+count});
    $('input[name="_amount['+count+']"]').attr({"required": "true", 'id': 'table2_amount_'+count});
    $('select[name="_reasonInject['+count+']"]').addClass('select2').attr('data-id', count);
    $('.select2').select2();
    count++;
}
function addValueItem(content, amount, reasonInject, count, idItem = ""){
    $('input[name="_content['+count+']"]').val(content);
    $('input[name="_amount['+count+']"]').val(amount);
    $('select[name="_reasonInject['+count+']"]').val(reasonInject).change();
    $('input[name="_idItem['+count+']"]').val(idItem);
}
// ajax template
function template(e , idElement , table){
    var id = e.dataset.id;
    var container = $("#"+idElement);
    $.ajax({
        url: '/admin/template',
        type: 'POST',
        context: e,
        data: {'search' : e.value},
    })
    .done(function(res) {
        if(res.status == 'success'){
                container.empty();
                container.append(replaceTemplace(res.data, id ,table));
                loadDatepicker();
        }else{
                container.empty();
        }
    })
}

// replace template 
function replaceTemplace(str , id = null , table = ""){
    var result = str.replace(/\[##Text##\]/g,'<input type="text" name="'+table+'_parameters['+id+'][]" class="form-control text-template p-1" style="max-width:150px" required />');
    result = result.replace(/\[##Date##\]/g,'<input type="text" name="'+table+'_parameters['+id+'][]" class="form-control date-template datepicker2 p-1" style="max-width:150px" required />');
    var nameItem = $('#'+table+'_name_'+id).val() ;
    nameItem = nameItem ? nameItem : "";
    result = result.replace(/\[##nameItem##\]/g,'<input type="text" name="'+table+'_parameters['+id+'][]" class="'+table+'_nameItem_'+id+' form-control text-template p-1" value="'+nameItem+'" style="max-width:150px" required readonly/>');
    var amountItem = $('#'+table+'_amount_'+id).val() ;
    amountItem = amountItem ? amountItem : " ";
    result = result.replace(/\[##amountItem##\]/g,'<input type="text" name="'+table+'_parameters['+id+'][]" class="'+table+'_amountItem_'+id+' form-control text-template p-1" value="'+amountItem.replace(/(,)/gm, ".")+'" style="max-width:150px" required readonly/>');
    result = result.replace(/\[Begin\]|\[End\]/g,'');
    return result;
}

function binding2Input(e , classElement){
    $('.'+classElement).val(e.value.replace(/(,)/gm, "."));
}

var wrapper         = $(".input_fields_wrap"); //Fields wrapper
var add_button      = $(".add_field_button"); //Add button ID

$(add_button).click(function(e){ //on add input button click
    e.preventDefault();
    $(wrapper).append('<div class = "row mt-2"><input type="text" name="request_qa[]" class="form-control col-md-11"/><button type="button" class="col-md-1 remove_field_btn btn btn-danger">X</button></div>'); //add input box

});

$('.add_benefit_button').click(function(e){ //on add input button click
    e.preventDefault();
    var benefit_content = $( "#benefit_content option:selected" ).text();
    var benefit_to = $( "#benefit_to" ).val();
    var benefit_amount = $( "#benefit_amount" ).val();
    
    add_benefit(benefit_content, benefit_to, benefit_amount);

});

$('#tab-list').on('click','.close',function(){
        var tabID = $(this).parents('a').attr('href');
        console.log(tabID);
        $(this).parents('li').remove();
        $(tabID).remove();

        //display first tab
        add_amt();
});
var count_benefit = 1;
function add_benefit(benefit_content , benefit_to , benefit_amount , default_benefit = null){
    if(default_benefit == null){
        var tabid = benefit_content+count_benefit;
        var benefit_title = benefit_content +"-"+ benefit_to;
    }else{
        var tabid = 'defaultBenefit';
        var benefit_title = 'Default';
    }
    
    //link clone
    var html = $("#clone-nav-link").clone().html();
        html = html.replace(/_benefit_title/gm, benefit_title);
        html = html.replace(/tab_benefit/gm, tabid);
    $('#tab-list').append(html);

    // clone-tab-content
    var html_content = $("#clone-tab-content").clone().html();
        html_content = html_content.replace(/tab_benefit/gm, tabid);
        html_content = html_content.replace(/_select2/gm, "select2");
        html_content = html_content.replace(/_benefit/gm, "benefit["+count_benefit+"]");
        html_content = html_content.replace(/clone_item/gm, "clone_item_"+tabid);
        
        $('#field_benefit').append(html_content);
        if(default_benefit == null){
            $('input[name="benefit['+count_benefit+'][content]"]').val(benefit_content);
            $('input[name="benefit['+count_benefit+'][to]"]').val(benefit_to);
            $('input[name="benefit['+count_benefit+'][amount]"]').val(benefit_amount);
            $('input[name="benefit['+count_benefit+'][key]"]').val(count_benefit);
        }else{
            $('input[name="benefit['+count_benefit+'][content]"]').remove();
            $('input[name="benefit['+count_benefit+'][to]"]').remove();
            $('input[name="benefit['+count_benefit+'][amount]"]').remove();
            $('input[name="benefit['+count_benefit+'][key]"]').remove();
        }
    add_amt();
    count_benefit++;
    
}
$('.remove_field').click(function(e){ //user click on remove text
    e.preventDefault(); $(this).parent('div').remove(); 
})
$(document).ready(function() {


    //type of visit
        var count_type = 1;
        $("#add_button_type_of_visit").click(function(e){ //on add input button click
            e.preventDefault();
            var clone = $('.input_fields_type_of_visit').clone().html();
            var clone = $('.input_fields_type_of_visit').clone().html();
            clone = clone.replace(/_type_of_visit/gm, "type_of_visit["+count_type+"]");
            $("#type_of_visit").append(clone);
            count_type++;

        });
        
        var data_type_of_visit = @json($claimWordSheet->type_of_visit);
        $.each(data_type_of_visit, function (index, value) {
            var clone = $('.input_fields_type_of_visit').clone().html();
            clone = clone.replace(/_type_of_visit/gm, "type_of_visit["+count_type+"]");
            $("#type_of_visit").append(clone);
            $('input[name="type_of_visit['+count_type+'][from]"]').val(value.from);
            $('input[name="type_of_visit['+count_type+'][to]"]').val(value.to);
            $('input[name="type_of_visit['+count_type+'][diagnosis]"]').val(value.diagnosis);
            $('input[name="type_of_visit['+count_type+'][prov_name]"]').val(value.prov_name); 
            count_type++;
        });
    //end type of visit
    
    // benefi and reject
    var data_benefit = @json($claimWordSheet->benefit);
    $.each(data_benefit, function (index, value) {
        add_benefit(value.content, value.to, value.amount);          
    });

    var item_of_claim = @json($claim->item_of_claim);
    
    if(item_of_claim != null){
        $.when(
            $.each(item_of_claim, function (index, value) {
                if(value.benefit == null || value.benefit == 'defaultBenefit'){
                    if( $('#defaultBenefit').length == 0 ){
                        add_benefit(" ", " ", " ", 1)
                    }
                    addInputItem("defaultBenefit");
                    addValueItem(value.content,value.amount,value.reason_reject_id,count-1,value.idItem);
                }else{
                    addInputItem(value.benefit);
                    addValueItem(value.content,value.amount,value.reason_reject_id,count-1,value.idItem);
                }
                
            })
        ).then(function () {
            var table2_parameters = @json(old('table2_parameters')? array_values(old('table2_parameters')) : old('table2_parameters')) ;
            table2_parameters = table2_parameters ? table2_parameters : @json($claim->item_of_claim->pluck('parameters'));
            if(table2_parameters != null){
                setTimeout(() => {
                    $.each(table2_parameters, function (index, value) {
                        var i = parseInt(index)+1;
                        var el = $('input[name="table2_parameters['+i+'][]"]');
                        $.each(el, function (index2, value2) {
                            $(this).val(value[index2]);
                        });
                    });
                }, 2000);
            }
        });
    }
    
    add_amt();
});

$(document).on("click", ".remove_field_btn", function(){
    $(this).parent('div').remove();
    add_amt();
});
$(document).on("change", ".reject_input", function(){
    add_amt();
});

$(document).on("click", ".delete_btn", function(){
    add_amt();
});

function add_amt(){
    var sumrj = 0 ;
    var sumbe = 0 ;
    $( ".benefit_input" ).each(function( index ) {
        sumbe += parseInt(removeFormatPrice($( this ).val() == '' ? 0 : $( this ).val()));
    });
    $( ".reject_input" ).each(function( index ) {
        sumrj += parseInt(removeFormatPrice($( this ).val() == '' ? 0 : $( this ).val()));
    });
    
    $("#claim_amt").val(sumbe);
    $("#payable_amt").val(sumbe-sumrj);
}

function upload_summary(){
    $(".loader").show();
    axios.get("{{route('claimWordSheets.summary', $claimWordSheet->id)}}")
    .then(function (response) {
        $(".loader").fadeOut("slow");
        console.log(response);
        $.notify({
            icon: 'fa fa-bell',
            title: '<strong>Hệ Thống</strong>',
            message: response.data.message
        },{
            placement: {
                from: "top",
                align: "right"
            },
            type: 'success'
        });
        
    })
    .catch(function (error) {
        $(".loader").fadeOut("slow");
        alert(error);
    });
}

function sendSortedFile(){
    $(".loader").show();
    axios.get("{{route('claimWordSheets.sendSortedFile', $claimWordSheet->id)}}")
    .then(function (response) {
        $(".loader").fadeOut("slow");
        console.log(response);
        $.notify({
            icon: 'fa fa-bell',
            title: '<strong>Hệ Thống</strong>',
            message: response.data.message
        },{
            placement: {
                from: "top",
                align: "right"
            },
            type: 'success'
        });
        
    })
    .catch(function (error) {
        $(".loader").fadeOut("slow");
        alert(error);
    });
}

</script>

    
@endsection
