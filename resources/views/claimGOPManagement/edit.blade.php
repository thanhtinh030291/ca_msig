<!-- Stored in resources/views/layouts/admin/partials/top_bar_navigation.blade.php -->
@extends('layouts.admin.master')
@section('title', __('message.transport_edit'))
@section('stylesheets')
<link href="{{asset('css/fileinput.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
<link href="{{asset('css/formclaim.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
@include('layouts.admin.breadcrumb_index', [
    'title'       => __('message.claim_edit'),
    'parent_url'  => route('claim.index'),
    'parent_name' => __('message.claim_management'),
    'page_name'   => __('message.claim_edit'),
])
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                {{ Form::open(array('url' => '/admin/claim/'.$data->id, 'method'=>'post', 'files' => true))}} @method('PUT')
                <!-- Create tour -->
                {{ Form::hidden('_del_image' ,null, ['class' => 'form-control' , 'id'=> '_del_image']) }}<br/>
                <!-- Add file image -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-3">
                                {{ Form::label('file', 'File ORC', array('class' => 'labelas')) }} <span class="text-danger">*(CSV )</span>
                                <div class="file-loading">
                                    <input id="dataImg" type="file" name="_image[]" multiple >
                                </div>
                            </div>

                            <div class="col-md-3">
                                {{ Form::label('_url_file_sorted', 'Tệp đã được sắp sếp', array('class' => 'labelas')) }} <span class="text-danger">*(PDF)</span>
                                <div class="file-loading">
                                    <input id="url_file_sorted" type="file" name="_url_file_sorted[]" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                {{ Form::label('code_claim', __('message.code_claim'), array('class' => 'labelas')) }} <span class="text-danger">*</span>
                                {{ Form::select('code_claim',$listCodeClaim,$data->code_claim, array('class' => 'code_claim form-control', 'required')) }}
                                <div class="card">
                                    <h5 class="card-header">Applicant Information</h5>
                                    <div class="card-body" id="result_applicant">
                                </div>
                                {{ Form::label('member_name', 'Member Name', array('class' => 'labelas')) }}
                        {{ Form::text('member_name', old('member_name'), array('class' => 'member_name form-control')) }}
                        {{ Form::label('code_claim_show', 'Code Claim', array('class' => 'labelas')) }}
                        {{ Form::text('code_claim_show', old('code_claim_show'), array('class' => 'code_claim_show form-control')) }}
                        {{ Form::label('barcode', 'Barcode', array('class' => 'labelas')) }}
                        {{ Form::text('barcode', old('barcode'), array('id'=>'barcode', 'class' => 'barcode form-control', 'required', 'readonly')) }}
                        </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-sm-2 col-form-label ">
                                {{ Form::label('new_items_reject', __('message.new_items_reject'), array('class' => 'card-title')) }}
                            </div>
                            <div class="col-sm-9">
                                <button type="button" class="btn btn-secondary mt-2 btnt" onclick="addInputItem()">{{ __('message.add')}}</button>
                            </div>
                        </div>
                        <div class="row">
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
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <a class="btn btn-secondary btnt" href="{{url('admin/claim')}}">
                        {{ __('message.back')}}
                    </a>
                    <button type="submit" class="btn btn-danger center-block btnt" style="margin-left: 20px"> {{__('message.save')}}</button> <br>
                </div>
                <!-- End file image -->
                {{ Form::close() }}
            </div>
        </div>
    </div>
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
<script>
var dataImage = @json($dataImage);
var previewConfig = @json($previewConfig);
var maxFile = 1;
$('#dataImg').fileinput({
    maxFileCount: maxFile,
    overwriteInitial: true,
    validateInitialCount: true,
    initialPreview: dataImage,
    initialPreviewConfig: previewConfig,
});

var url_file_sorted =  [' {{ $data->url_file_sorted ?  asset("") . config('constants.sotedClaimStorage') . $data->url_file_sorted  : ''}} '];

$("#url_file_sorted").fileinput({
    uploadAsync: false,
    
    maxFileCount: 1,
    overwriteInitial: true,
    initialPreview: url_file_sorted,
    initialPreviewAsData: true, // identify if you are sending preview data only and not the raw markup
    initialPreviewFileType: 'image', // image is the default and can be overridden in config below
    initialPreviewDownloadUrl: 'https://kartik-v.github.io/bootstrap-fileinput-samples/samples/{filename}', // includes the dynamic `filename` tag to be replaced for each config
    initialPreviewConfig: [
        {type: "pdf", size: 8000, caption: "sumaryFile.pdf",  key: 1, downloadUrl: false}, // disable download
    ],
    purifyHtml: true, // this by default purifies HTML data for preview
    uploadExtraData: {
        img_key: "1000",
        img_keywords: "happy, places"
    }
});
var str_key = [];
$('#dataImg').on('filedeleted', function(event, key, jqXHR, data) {
    str_key.push(key);
    $('#_del_image').val(str_key);
});

$(document).on('ready', function() {
    type_ahead();
    icheck_fn();
    var content = @json(old('_content'));
    content = content ? content : @json($data->item_of_claim->pluck('content'));
    var amount = @json(old('_amount'));
    amount = amount ? amount : @json($data->item_of_claim->pluck('amount'));
    var reasonInject = @json(old('_reasonInject'));
    reasonInject = reasonInject ? reasonInject : @json($data->item_of_claim->pluck('reason_reject_id'));
    var idItem = @json(old('_idItem'));
    idItem = idItem ? idItem : @json($data->item_of_claim->pluck('id'));
    if(content != null){
        $.each(content, function (index, value) {
            addInputItem();
            addValueItem(content[index],amount[index],reasonInject[index],count-1,idItem[index]);
        });
    }
    var table2_parameters = @json(old('table2_parameters')? array_values(old('table2_parameters')) : old('table2_parameters')) ;
    table2_parameters = table2_parameters ? table2_parameters : @json($data->item_of_claim->pluck('parameters'));
    if(table2_parameters != null){
        setTimeout(function(){
            $.each(table2_parameters, function (index, value) {
                var i = parseInt(index)+1;
                var el = $('input[name="table2_parameters['+i+'][]"]');
                $.each(el, function (index2, value2) {
                    $(this).val(value[index2]);
                });
            });
        },1500);
    }
});
</script>


<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var clipboard = new ClipboardJS('.btn');
    clipboard.on('success', function(e) {
        if(idPaste){
            $("#_content"+idPaste).val(e.text);
            removeIdPaste();
        }
        else{
            alert('Please select the region to paste');
        }
    });
</script>

@endsection
