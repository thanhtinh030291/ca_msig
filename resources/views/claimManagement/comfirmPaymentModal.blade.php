<div id="comfirmPaymentModal" class="modal fade bd-example-modal-lg" role="dialog">
    <div class="modal-dialog modal-lg">
        
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Comfirm Letter </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                {{ Form::open(array('url' => '/admin/requestLetter', 'method' => 'POST')) }}
                    {{ Form::hidden('claim_id', $data->id ) }}
                    {{ Form::hidden('letter_template_id', null, array('id'=>'LetterTemplateId', 'class' => 'form-control')) }}

                    {{ Form::text(null, null, array('id' => 'textLetter','class' => 'form-control', 'readonly')) }}<br>

                    
                    {{-- HBS --}}
                    <div class="row mb-2">
                        {{ Form::label('type',  'Apr Amt HBS' , array('class' => 'col-md-2')) }} 
                        {{ Form::text('apv_hbs', null , array('id' => 'apv_hbs_in','class' => 'col-md-4 item-price form-control', 'readonly')) }}
                    </div>
                    {{-- mantis --}}
                    <div class="row  mb-2">
                        {{ Form::label('type',  'Payment History' , array('class' => 'col-md-2')) }} 
                        <table id="season_price_tbl" class="table table-striped header-fixed col-md-8">
                            <thead>
                                <tr>
                                    <th>Datetime</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="empty_item" style="display: none;">
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr id="clone_item" style="display: none;">
                                    <td>_text_default</td>
                                    <td>
                                        {{ Form::text('_amount_default', '_amount_value_default', ['class' => 'item-price form-control p-1 history_amt' , 'readonly']) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    {{-- Cấn trừ --}}
                    <div class="row mb-2">
                        {{ Form::label('PCV_EXPENSE',  'PCV EXPENSE' , array('class' => 'col-md-2')) }}
                        {{ Form::text('PCV_EXPENSE', null , array('id' => 'PCV_EXPENSE','class' => 'col-md-4 item-price form-control', 'readonly' ,'onchange' => "amount_letter_print()")) }}
                    </div>
                    <div class="row mb-2">
                        {{ Form::label('type',  'DEBT BALANCE' , array('class' => 'col-md-2')) }}
                        {{ Form::text('DEBT_BALANCE', null , array('id' => 'DEBT_BALANCE','class' => 'col-md-4 item-price form-control', 'readonly' ,'onchange' => "amount_letter_print()")) }}
                    </div>
                    <button type="button" class="btn btn-primary" onclick="comfirmPayment()"><i class="fa fa-refresh" aria-hidden="true"></i></button>
                    {{-- Print letter --}}
                    <div class="row mb-2">
                        {{ Form::label('type',  'Amount in Payment Letter' , array('class' => 'text-white col-md-4 bg-secondary')) }} 
                        {{ Form::text('amount_letter', null , array('id' => 'amount_letter','class' => 'h5 text-danger col-md-4 bg-secondary item-price', 'readonly' )) }}
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