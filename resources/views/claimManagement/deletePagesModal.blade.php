<div id="deletePagesModal" class="modal fade bd-example-modal-lg" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            {{ Form::open(array('url' => '/admin/claim/deletePage/'.$data->id, 'method' => 'POST')) }}
                <div class="modal-header">
                    <h4 class="modal-title">Xóa Trang Trong tệp đã xắp xếp </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    {{ Form::label('from', 'From page', array('class' => 'labelas')) }}
                    {{ Form::text('from', null, array('class' => 'form-control')) }}
                    {{ Form::label('to', 'To page', array('class' => 'labelas')) }}
                    {{ Form::text('to', null, array( 'class' => 'form-control', 'required')) }}
                    <div class="row">
                        <div id = 'button_save' class="pull-right">
                            <button class="btn btn-danger"> Submit</button> 
                            <button type="button" class="btn btn-secondary btn-cancel-delete" 
                                data-dismiss="modal">Close</button>
                        </div><br>
                    </div>
                    
                </div>
                <div class="modal-footer">
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>