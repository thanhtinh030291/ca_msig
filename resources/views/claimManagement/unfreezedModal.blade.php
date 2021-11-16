<div id="unfreezedModal" class="modal" role="dialog">
    <div class="modal-dialog">
        
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Xác Nhận Gỡ Đóng Băng Claim</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                {{ Form::open(array('url' => '/admin/unfreezed/'.$data->id, 'method' => 'POST')) }}
                    {{-- HBS --}}
                    <div class="row mb-2">
                        {{ Form::label('reason',  'Chọn mục đích mở khóa' , array('class' => 'col-md-4')) }} 
                        {{ Form::select('reason', config('constants.reason_unfreezed') ,null, array('id' => 'apv_hbs_in','class' => 'col-md-8 form-control')) }}
                    </div>
                    <div class="row mb-2">
                        {{ Form::label('desc',  'Mô Tả Thêm' , array('class' => 'col-md-4')) }} 
                        {{ Form::textarea('desc', null, array('id' => 'apv_hbs_in','class' => 'col-md-8 form-control')) }}
                    </div>
                    <div class="row">
                        <div id = 'button_save' class="pull-right">
                            <button class="btn btn-danger" name="save_letter" value="save"> OK</button> 
                            <button type="button" class="btn btn-secondary btn-cancel-delete" 
                                data-dismiss="modal">Close</button>
                        </div><br>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>