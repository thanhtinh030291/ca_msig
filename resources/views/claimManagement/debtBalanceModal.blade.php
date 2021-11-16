<div id="debtBalanceModal" class="modal fade bd-example-modal-lg" role="dialog">
    <div class="modal-dialog modal-lg">
        
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Quá trình ghi nợ của Khách Hàng</h4>
                
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>    
                <div class="modal-body ">
                    @if($balance_cps->sum('DEBT_BALANCE') > 0)
                        <p class="text-danger">Khách Hàng đang nợ có thể đòi : <span class="font-weight-bold">{{formatPrice($balance_cps->sum('DEBT_BALANCE'), ' đ')}}</span></p>
                        {{ Form::open(array('url' => '/admin/claim/payDebt/'.$data->id, 'method'=>'post', 'files' => true, 'style' => 'width: 100%;'))}}
                            <div class="row">
                                {{ Form::hidden('memb_name',  $member_name ) }}
                                {{ Form::hidden('cl_no',  $data->code_claim_show ) }}
                                {{ Form::hidden('memb_ref_no',  $memb_ref_no ) }}
                                {{ Form::text('paid_amt', null , array('class' => 'item-price form-control col-md-4' )) }}
                                <button class=""><i class="fa fa-user-circle-o col-md-4" aria-hidden="true"></i> KH Đã Trả</button>
                            </div>
                        {{ Form::close() }}
                    @endif
                    <table id="debtBalanceTable" class="table table-bordered table-hover display">
                        <thead>
                            <tr>
                                <th>TRAN_ID</th>
                                <th>MEMB NAME</th>
                                <th>MEMB REF NO</th>
                                <th>DEBT TYPE</th>
                                <th>CLAIM NO</th>
                                <th>PCV_EXPENSE (không đòi)</th>
                                <th>DEBT_AMT (được đòi)</th>
                                <th>PAID_AMT (đã trả)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>		
                        @foreach ($balance_cps as $item)
                            <tr>
                                <td>{{data_get($item,'TRAN_ID')}}</td>
                                <td>{{data_get($item,'MEMB_NAME')}}</td>
                                <td>{{data_get($item,'MEMB_REF_NO')}}</td>
                                <td>{{data_get(config('constants.debt_type'),data_get($item,'DEBT_TYPE'))}}</td>
                                <td>{{data_get($item,'DEBT_CL_NO')}}</td>
                                <td>{{formatPrice(data_get($item,'PCV_EXPENSE'))}}</td>
                                <td>{{formatPrice(data_get($item,'DEBT_AMT'))}}</td>
                                <td>{{formatPrice(data_get($item,'PAID_AMT'))}}</td>
                                <td>
                                    @if(data_get($item,'DEBT_TYPE') == 4)
                                    {{ Form::open(array('url' => '/admin/claim/setDebt/'.$data->id, 'method'=>'post', 'files' => true, 'style' => 'width: 100%;'))}}
                                        <div class="row">
                                            {{ Form::hidden('debt_id',  data_get($item, 'DEBT_ID') ) }}
                                            <button class=""><i class="fa fa-refresh" aria-hidden="true"></i> Đòi Được</button>
                                        </div>
                                    {{ Form::close() }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>	
                    </table>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div id = 'button_save' class="pull-right">
                            
                            <button type="button" class="btn btn-secondary btn-cancel-delete" 
                                data-dismiss="modal">Close</button>
                        </div><br>
                    </div>
                </div>
        </div>
    </div>
</div>