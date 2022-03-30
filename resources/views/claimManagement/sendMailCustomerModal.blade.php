<div id="sendMailCustomerModal" class="modal fade bd-example-modal-lg" role="dialog">
    <div class="modal-dialog modal-lg">
        
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Send Mail For Custommer </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                {{ Form::open(array('url' => '/admin/claim/sendMailCustomer', 'method'=>'post', 'files' => true))}}
                {{ Form::hidden('export_letter_id', null ,['id' => 'letter_email_id']) }}
                {{ Form::hidden('claim_id', $data->id ,['class' => 'claim_id']) }}
                {{ Form::label('email_to', 'To Email', ['class' => 'labelas mt-1']) }}
                {{ Form::text('email_to', $fromEmail, array('id' => 'email_to','class' => 'tag-editor form-control ', 'required', 'data-role' => 'tagsinput')) }}<br>
                {{ Form::label('bcc', 'BCC', ['class' => 'labelas mt-1']) }}
                {{ Form::text('bcc', $bcc, array('id' => 'bcc','class' => 'tag-editor form-control ', 'required', 'data-role' => 'tagsinput')) }}<br>
                
                <div class="row">
                    <div id = 'button_save' class="pull-right">
                        {!! Form::button('Ok', ['type' => 'submit','name'=>'type_submit','value' => 'reject','class' => ' btn btn-info' ]) !!}
                        <button type="button" class="btn btn-secondary btn-cancel-delete" 
                            data-dismiss="modal">Close</button>
                    </div><br>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>