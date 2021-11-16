<div id="updateStatusEtalkModal" class="modal" role="dialog">
    <div class="modal-dialog">
        
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cập Nhật trạng thái Etalk</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <h6>Health Etalk sẽ tự động cập nhật trạng thái dựa vào số tiền bạn nhập ở HBS.</h6>
                {{ Form::open(array('url' => '/admin/changeStatusEtalk', 'method' => 'POST')) }}
                    {{-- HBS --}}
                    {{ Form::hidden('claim_id', $data->id) }}
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