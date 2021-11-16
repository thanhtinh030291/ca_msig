
<div class="card-header p-1 text-danger">
    Invoice Type
</div>
<div class="card-body">
    {{ Form::open(array('url' => '/admin/claim/update_invoice/'.$data->id, 'method'=>'post', 'files' => true))}}
    
    @foreach (config('constants.invoice_type') as $key => $item)
        
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="vat_type[]" id="{{$key}}" value="{{$key}}" 
            {{data_get($data,$key."_no") ? "checked" : ""}}>
            <label class="form-check-label" for="exampleRadios1">
                {{$item}}
            </label>
        </div>
    @endforeach
    <div id="original_invoice" class="original_invoice vat_type border border-warning p-3 mt-2" style="{{(data_get($data,"original_invoice_no") || data_get($data,"original_invoice_no_not_ready")) ? "" : "display:none"}}">
        {{ Form::label('title', 'Hóa đơn góc ', array('class' => 'labelas')) }}<br />
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="original_invoice_type" value="No" checked>
            Đợi bổ sung
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="original_invoice_type" value="Yes" {{$data->original_invoice_type =='Yes' ? "checked" : ""}}>
            Đầy đủ
        </div>
        <br />
        {{ Form::label('original_invoice_no', 'Số hóa đơn (Đầy đủ)', array('class' => 'labelas')) }}<span class='text-danger'>*</span>
        {{ Form::text('original_invoice_no', $data->original_invoice_no, [ 'class' => 'tag-editor form-control','placeholder' =>'mã hóa đơn']) }}<br/>

        {{ Form::label('original_invoice_no_not_ready', 'Số hóa đơn (Đợi bổ sung)', array('class' => 'labelas')) }}<span class='text-danger'>*</span>
        {{ Form::text('original_invoice_no_not_ready', $data->original_invoice_no_not_ready, [ 'class' => 'tag-editor form-control','placeholder' =>'mã hóa đơn']) }}
    </div>
    <div id="e_invoice" class="e_invoice vat_type border border-warning p-3 mt-2" style="{{ (data_get($data,"e_invoice_no") || data_get($data,"e_invoice_no_not_ready"))? "" : "display:none"}}">
        {{ Form::label('title', 'Hóa đơn điện tử ', array('class' => 'labelas')) }}<br />
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="e_invoice_type" value="No" checked>
            Chưa Hợp lệ
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="e_invoice_type" value="Yes" {{$data->e_invoice_type =='Yes' ? "checked" : ""}}>
            Hợp lệ
        </div>
        
        <a href="https://einvoice.vn/tra-cuu" target="_blank">https://einvoice.vn/tra-cuu</a>
        <br />
        {{ Form::label('e_invoice_no', 'Số hóa đơn (hợp lệ)', array('class' => 'labelas')) }}<span class='text-danger'>*</span>
        {{ Form::text('e_invoice_no', $data->e_invoice_no, [ 'class' => 'tag-editor form-control','placeholder' =>'mã hóa đơn']) }}<br/>
        {{ Form::label('e_invoice_no_not_ready', 'Số hóa đơn (Không hợp lệ)', array('class' => 'labelas')) }}<span class='text-danger'>*</span>
        {{ Form::text('e_invoice_no_not_ready', $data->e_invoice_no_not_ready, [ 'class' => 'tag-editor form-control','placeholder' =>'mã hóa đơn']) }}
    </div>
    <div id="converted_invoice" class="vat_type converted_invoice border border-warning p-3 mt-2" style="{{(data_get($data,"converted_invoice_no") || data_get($data,"converted_invoice_no_not_ready")) ? "" : "display:none"}}">
        {{ Form::label('title', 'Hóa đơn chuyển đổi ', array('class' => 'labelas')) }}<br />
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="converted_invoice_type" value="No">
            Chưa Hợp lệ
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="converted_invoice_type" value="Yes" {{$data->converted_invoice_type =='Yes' ? "checked" : ""}}>
            Hợp lệ
        </div>
        
        <br />
        {{ Form::label('original_invoice_no', 'Số hóa đơn (hợp lệ)', array('class' => 'labelas')) }}<span class='text-danger'>*</span>
        {{ Form::text('converted_invoice_no', $data->converted_invoice_no, [ 'class' => 'tag-editor form-control','placeholder' =>'mã hóa đơn']) }}<br/>
        {{ Form::label('original_invoice_no_not_ready', 'Số hóa đơn (Không hợp lệ)', array('class' => 'labelas')) }}<span class='text-danger'>*</span>
        {{ Form::text('converted_invoice_no_not_ready', $data->converted_invoice_no_not_ready, [ 'class' => 'tag-editor form-control','placeholder' =>'mã hóa đơn']) }}<br/>
    </div>
    <button type="submit" class="btn btn-primary float_left " > {{__('message.save')}}</button>
    @if ($btn_notication)
            {!! Form::button('Notication for mobile', ['data-toggle' => "modal" ,  
            'data-target' => "#invoiceNoticationModal",
            'type' => 'button', 
            'class' => 'btn btn-info' , 
            
            ]) !!}
    @endif
    {{ Form::close() }}
</div>
