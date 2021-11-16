<div id="previewModal" class="modal fade bd-example-modal-lg" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            {{ Form::open(array('url' => '/admin/waitCheck', 'method' => 'POST')) }}
                {{ Form::hidden('id', null ,['class' => 'export_letter_id']) }}
                {{ Form::hidden('claim_id', null ,['class' => 'ex_claim_id']) }}

                <div class="modal-header">
                    <h4 class="modal-title">Preview</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    {{ Form::textarea('template', old('template'), ['id' => 'preview_letter', 'class' => 'form-control editor_readonly']) }}<br>
                </div>
                <div class="modal-footer">
                    {{ Form::hidden('status', config('constants.statusExport.new')) }}<br>
                    
                        <button class="btn btn-danger" name="save_letter" value="save">{{ __('message.yes')}} </button>
                        <button type="button" class="btn btn-secondary btn-cancel-delete" 
                            data-dismiss="modal">{{ __('message.no') }}</button>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>