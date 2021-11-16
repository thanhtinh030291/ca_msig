<div id="noteOrEditModal" class="modal fade bd-example-modal-lg" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            {{ Form::open(array('url' => '/admin/waitCheck', 'method' => 'POST')) }}
                {{ Form::hidden('id', null ,['class' => 'export_letter_id']) }}
                {{ Form::hidden('claim_id', null ,['class' => 'ex_claim_id']) }}

                <div class="modal-header">
                    <h4 class="modal-title">Note of Reject </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    {{ Form::textarea('template', old('template'), ['id' => 'note_letter', 'class' => 'form-control editor_default']) }}<br>
                    {{ Form::hidden('status', config('constants.statusExport.note_save'), ['id' => 'statusSubmit']) }}<br>
                    <div class="row">
                        <div id = 'button_save' class="pull-right">
                            <button class="btn btn-danger" name="save_letter" value="save"> Save Letter</button> 
                            <button type="button" class="btn btn-secondary btn-cancel-delete" 
                                data-dismiss="modal">Close</button>
                        </div><br>
                    </div>
                    
                </div>
                <div class="modal-footer bg-info">
                    <h4 class="modal-title"> Change Status </h4>
                    <div id='button_items'>
                    </div>
                    <div id="button_clone" style="display: none">
                        <button class="btn btn-secondary m-1" name = 'status_change' value = 'value_default'> text_default </button> 
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>