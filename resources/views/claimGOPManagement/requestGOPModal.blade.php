<div id="requestGOPModal" class="modal fade bd-example-modal-lg" role="dialog">
    <div class="modal-dialog modal-lg">
        
        <!-- Modal content-->
        <div class="modal-content">
            
            <div class="modal-header">
                <h4 class="modal-title">Nhập Dữ Liệu Đầu Vào</h4>

                <button type="button" class="close" data-dismiss="modal">&times;</button>
                
                
            </div>    
                        
            <div class="modal-body ">
                {{ Form::open(array('url' => '/admin/claim/setProvGOPPresAmt/'.$data->id , 'method' => 'POST','files' => true, "id" =>  'requestGOPForm', 'class' => 'dropzone')) }}
                <div class="row">
                    {{ Form::hidden('cl_no', $data->code_claim_show ) }}
                    {{ Form::hidden('id_claim', $data->code_claim ,['class' => 'id_claim']) }}
                    {{ Form::label('prov_gop_pres_amt', 'Tổng Số Tiền Yêu Cầu Ban Đầu', ['class' => 'labelas col-md-4 mt-1']) }}
                    {{ Form::text('prov_gop_pres_amt', data_get($hospital_request,'prov_gop_pres_amt') ? data_get($hospital_request,'prov_gop_pres_amt') : $present_amt, ['class' => 'item-price form-control col-md-5 mt-1 prov_gop_pres_amt', 'oninput' => 'gop_pres_amt_change()',"required"]) }}

                    {{ Form::label('prov_gop_pres_amt_hbs', 'Present Amt(HBS)', ['class' => 'labelas col-md-4 mt-1']) }}
                    {{ Form::text('_prov_gop_pres_amt_hbs', $present_amt, ['class' => 'item-price form-control col-md-5 mt-1 prov_gop_pres_amt', 'oninput' => 'gop_pres_amt_change()',"readonly"]) }}

                    {{ Form::label('incur_from', 'Thời Gian nằm viện Từ ', ['class' => 'labelas col-md-4 mt-1']) }}
                    {{ Form::text('incur_from', data_get($hospital_request,'incur_from'), ['class' => 'form-control col-md-5 mt-1 ']) }}

                    {{ Form::label('incur_to', 'Thời Gian nằm viện Đến', ['class' => 'labelas col-md-4 mt-1']) }}
                    {{ Form::text('incur_to', data_get($hospital_request,'incur_to'), ['class' => 'form-control col-md-5 mt-1 ']) }}

                    {{ Form::label('incur_time', 'Tổng Thời Gian nằm viện', ['class' => 'labelas col-md-4 mt-1']) }}
                    {{ Form::text('incur_time', data_get($hospital_request,'incur_time'), ['class' => 'form-control col-md-5 mt-1 ']) }}

                    {{ Form::label('incur_time_extb', 'Tổng Thời Gian (EXTB)', ['class' => 'labelas col-md-4 mt-1']) }}
                    {{ Form::text('incur_time_extb', data_get($hospital_request,'incur_time_extb'), ['class' => 'form-control col-md-5 mt-1 ']) }}

                    {{ Form::label('diagnosis', 'Chuẩn Đoán', ['class' => 'labelas col-md-4 mt-1']) }}
                    {{ Form::text('diagnosis', data_get($hospital_request,'diagnosis'), ['class' => 'form-control col-md-5 mt-1 ']) }}
                    

                    {{ Form::label('prov_gop_pres_amt', 'File Yêu Cầu Bảo Đảm Viện Phí (PDF)', ['class' => 'labelas col-md-4 mt-1']) }}
                    <div class="col-md-5">
                        <div class="file-loading">
                            <input id="url_form_request" type="file" name="_url_form_request" >
                        </div>
                    </div>

                    {{ Form::label('type_gop', 'Kết Quả Bảo Lãnh', ['class' => 'labelas col-md-4 mt-1']) }}
                    {{ Form::select('type_gop', config('constants.gop_type'),data_get($hospital_request,'type_gop'), ['class' => 'form-control col-md-5 mt-1 ', "required"]) }}

                    {{ Form::label('note', 'Ghi Chú', ['class' => 'labelas col-md-4 mt-1']) }}
                    {{ Form::textarea('note', data_get($hospital_request,'note'), ['class' => 'form-control col-md-5 mt-1 ', 'rows' => "4"]) }}

                    {{ Form::label('new_items_reject', "Chi Phí Từ Chối", array('class' => 'card-title col-md-4 mt-1')) }}
                    <button type="button" class="btn btn-secondary col-md-1 mt-2 " onclick="addInputItemReject()">{{ __('message.add')}}</button>
                    <div class="col-md-4"></div>

                    <div class="col-md-4"></div>
                    <div class="col-md-5">
                        <table id="season_price_tbl" class="table table-striped header-fixed">
                            <thead>
                                <tr>
                                    <th>{{ __('message.content')}}</th>
                                    <th>{{ __('message.amount')}}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="empty_item_reject" style="display: none;">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr id="clone_item_reject" style="display: none;">
                                    <td style="width:230px">{{ Form::text('_content_default', null, ['class' => 'form-control p-1']) }}</td>
                                    <td style="width:230px">
                                        {{ Form::text('_amount_default', null, ['class' => 'item-price form-control p-1 reject_input', 'oninput' => 'gop_pres_amt_change()']) }}
                                    </td>
                                    <td>
                                        <button type="button" class="delete_btn btn btn-danger float-right p-2" style="height : 40px">&#x2613;</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    

                    {{ Form::label('APPAMTGOP', 'Chi Phí Bảo Lãnh Dự Kiến', ['class' => 'labelas col-md-4 mt-1']) }}
                    {{ Form::text('APPAMTGOP', $approve_amt, ['class' => 'app_amt_gop item-price form-control col-md-5 mt-1', 'readonly']) }}

                    
                    {{ Form::textarea('from', data_get($data->inbox_email, "from"), ['style' => 'display:none','class' => 'from_email form-control col-md-5 mt-1']) }}
                    {{ Form::textarea('to', implode("," ,data_get($data->inbox_email, "to",[])) , ['style' => 'display:none','class' => 'to_email  form-control col-md-5 mt-1']) }}    
                    {{ Form::textarea('subject', data_get($data->inbox_email, "subject") , ['style' => 'display:none','class' => 'subject_email  form-control col-md-5 mt-1']) }}
                    {{ Form::textarea('body', data_get($data->inbox_email, "body") , ['style' => 'display:none','class' => 'body_email form-control col-md-5 mt-1']) }}   

                    {{ Form::label('prov_gop_pres_amt', 'Đính kèm email (attach email .msg)', ['class' => 'labelas col-md-4 mt-1']) }}

                </div>
                <div class="fallback">
                    <input name="file" type="file" class="src-file"/>

                </div>
                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div  class="pull-right">
                        <button class="btn btn-danger" name="save_letter"  id="btn_submit" onclick="document.getElementById('requestGOPForm').submit();" value="save" > Submit</button> 
                        <button type="button" class="btn btn-secondary btn-cancel-delete" 
                            data-dismiss="modal">Close</button>
                    </div><br>
                </div>
            </div>
        </div>
    </div>
</div>