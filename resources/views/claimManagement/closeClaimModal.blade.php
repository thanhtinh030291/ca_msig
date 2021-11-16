<div id="closeClaimModal" class="modal" role="dialog">
    <div class="modal-dialog">
        
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Xác Nhận Close Claim</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                {{ Form::open(array('url' => '/admin/closeClaim/'.$data->id, 'method' => 'POST')) }}
                    {{-- HBS --}}
                    <div class="row mb-2">
                        {{ Form::hidden('reason','Close') }}
                    </div>
                    <div class="row mb-2">
                        {{ Form::label('desc',  'Mô Tả Thêm Nguyên Nhân' , array('class' => 'col-md-4')) }} 
                        {{ Form::textarea('desc', null, array('class' => 'col-md-8 form-control')) }}
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