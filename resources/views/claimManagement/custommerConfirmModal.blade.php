<div id="custommerConfirmModal" class="modal fade bd-example-modal-lg" role="dialog">
    <div class="modal-dialog modal-lg">
        
        <!-- Modal content-->
        <div class="modal-content">
            
            <div class="modal-header">
                <h4 class="modal-title">Atachment of Customer confirm</h4>

                <button type="button" class="close" data-dismiss="modal">&times;</button>
                
                
            </div>    
            {{ Form::open(array('url' => '/admin/custommerConfirm/'.$data->id , 'method' => 'POST','files' => true, "id" =>  'requestForm')) }}            
            <div class="modal-body ">
                
                <div class="row">
                    {{ Form::label('prov_gop_pres_amt', 'Atachment of Customer confirm', ['class' => 'labelas col-md-4 mt-1']) }}
                    <div class="col-md-5">
                        <div class="file-loading">
                            <input id="url_form_request" type="file" name="_url_form_request">
                        </div>
                    </div>
                </div>
                <div class="row mb-2 mt-2">
                    {{ Form::label('note',  'Mô Tả Thêm' , array('class' => 'col-md-4')) }} 
                    {{ Form::textarea('note', "Dear Team,  \n Khách Hàng đã xác nhận yêu cầu bồi thường \n Chi tiết tệp đính kèm \n Thanks,", array('id' => 'apv_hbs_in','class' => 'col-md-7 form-control')) }}
                </div>
                
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div  class="pull-right">
                        <button type='submit' class="btn btn-danger" name="save_letter"  id="btn_submit" value="save" > Submit</button> 
                        <button type="button" class="btn btn-secondary btn-cancel-delete" 
                            data-dismiss="modal">Close</button>
                    </div><br>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>