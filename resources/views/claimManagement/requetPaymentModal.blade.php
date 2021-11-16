<div id="requetPaymentModal" class="modal fade bd-example-modal-lg" role="dialog">
    <div class="modal-dialog modal-lg">
        
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Gửi Payment cho Finance để thanh toán</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>    
            {{ Form::open(array('url' => '/admin/claim/sendPayment/'.$data->id , 'method' => 'POST')) }}
                        {{ Form::hidden('cl_no', $data->code_claim_show ) }}
                <div class="modal-body ">
                    <p class="text-danger">Nếu số Liệu Chưa Chính xác gửi trả về , vui lòng run CSR lại và add note trên mantis </p>
                    <div class="row">
                        {{ Form::label('memb_name', 'Member Name(TÊN NGƯỜI ĐƯỢC BẢO HIỂM)', ['class' => 'labelas col-md-5 mt-1']) }}
                        {{ Form::text('memb_name', $member_name, ['class' => 'form-control col-md-5 mt-1']) }}
                    
                        {{ Form::label('pocy_no', 'Policy No(SỐ HỢP ĐỒNG)', ['class' => 'labelas col-md-5 mt-1']) }}
                        {{ Form::text('pocy_no', $pocy_no, ['class' => 'form-control col-md-5 mt-1', 'readonly']) }}
                    
                        {{ Form::label('memb_no', 'Member No(MÃ SỐ THÀNH VIÊN)', ['class' => 'labelas col-md-5 mt-1']) }}
                        {{ Form::text('memb_no', $memb_no, ['class' => 'form-control col-md-5 mt-1', 'readonly']) }}
                    
                        {{ Form::label('pres_amt', 'Presented Amt(SỐ TIỀN YÊU CẦU BỒI THƯỜNG)', ['class' => 'labelas col-md-5 mt-1']) }}
                        {{ Form::text('pres_amt', $present_amt, ['class' => 'form-control col-md-5 mt-1', 'readonly']) }}
                    
                        {{ Form::label('app_amt', 'Approved Amt(SỐ TIỀN BỒI THƯỜNG)', ['class' => 'labelas col-md-5 mt-1']) }}
                        {{ Form::text('app_amt', $approve_amt, ['class' => 'form-control col-md-5 mt-1', 'readonly']) }}
                    
                        {{ Form::label('tf_amt', 'Transfer Amt(SỐ TIỀN THANH TOÁN)', ['class' => 'labelas col-md-5 mt-1']) }}
                        {{ Form::text('tf_amt', $tranfer_amt, ['class' => 'form-control col-md-5 mt-1', 'readonly']) }}
                    
                        {{ Form::label('deduct_amt', 'Deduct Amt(Tiền khách Hàng nợ được đòi)', ['class' => 'labelas col-md-5 mt-1']) }}
                        {{ Form::text('deduct_amt', $balance_cps->sum('DEBT_BALANCE'), ['class' => 'form-control col-md-5 mt-1', 'readonly']) }}
                    
                        {{ Form::label('payment_method', 'Payment Method(PHƯƠNG THỨC THANH TOÁN)', ['class' => 'labelas col-md-5 mt-1']) }}
                        {{ Form::text('payment_method', $payment_method, ['class' => 'form-control col-md-5 mt-1', 'readonly']) }}
                    
                        {{ Form::label('mantis_id', 'Mantis id (Số ISSUE TRÊN H-E) ', ['class' => 'labelas col-md-5 mt-1']) }}
                        {{ Form::text('mantis_id', $data->mantis_id, ['class' => 'form-control col-md-5 mt-1', 'readonly']) }}
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div id = 'button_save' class="pull-right">
                            <button class="btn btn-danger" name="save_letter" value="save"> Send</button> 
                            <button type="button" class="btn btn-secondary btn-cancel-delete" 
                                data-dismiss="modal">Close</button>
                        </div><br>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>