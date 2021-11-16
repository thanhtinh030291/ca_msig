<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                History
            </div>
            <div class="card-body">
                <h5 class="card-title">Total Debt Amt : <span class="text-danger font-weight-bold">{{formatPrice($totalAmt)}}</span></h5>
                <table class="table">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Claim No</th>
                        <th scope="col">Amt</th>
                        <th scope="col">User</th>
                        <th scope="col">Comment</th>
                        <th scope="col">Create At</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($log_deduct as $item)
                        <tr>
                            <th>{{$item->id}}</th>
                            <td>{{$item->claim_no}}</td>
                            <td>{{$item->amt}}</td>
                            <td>{{$item->comment}}</td>
                            <td>{{data_get($admin_list, $item->created_user)}}</td>
                            <td>{{$item->created_at}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <a href="#" class="btn btn-primary">Close</a>
            </div>
        </div>
    </div>
</div>
<div class="row mt-2">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                Info 
            </div>
            <div class="row card-body">
                <!-- Id Field -->
                <div class="form-group col-md-4">
                    {!! Form::label('id', 'Id:') !!}
                    <p>{!! $provider->id !!}</p>
                </div>

                <!-- Prov Code Field -->
                <div class="form-group col-md-4">
                    {!! Form::label('PROV_CODE', 'Prov Code:') !!}
                    <p>{!! $provider->PROV_CODE !!}</p>
                </div>

                <!-- Eff Date Field -->
                <div class="form-group col-md-4">
                    {!! Form::label('EFF_DATE', 'Eff Date:') !!}
                    <p>{!! $provider->EFF_DATE !!}</p>
                </div>

                <!-- Term Date Field -->
                <div class="form-group col-md-4">
                    {!! Form::label('TERM_DATE', 'Term Date:') !!}
                    <p>{!! $provider->TERM_DATE !!}</p>
                </div>

                <!-- Prov Name Field -->
                <div class="form-group col-md-4">
                    {!! Form::label('PROV_NAME', 'Prov Name:') !!}
                    <p>{!! $provider->PROV_NAME !!}</p>
                </div>

                <!-- Addr Field -->
                <div class="form-group col-md-4">
                    {!! Form::label('ADDR', 'Addr:') !!}
                    <p>{!! $provider->ADDR !!}</p>
                </div>

                <!-- Scma Oid Country Field -->
                <div class="form-group col-md-4">
                    {!! Form::label('SCMA_OID_COUNTRY', 'Scma Oid Country:') !!}
                    <p>{!! $provider->SCMA_OID_COUNTRY !!}</p>
                </div>

                <!-- Payee Field -->
                <div class="form-group col-md-4">
                    {!! Form::label('PAYEE', 'Payee:') !!}
                    <p>{!! $provider->PAYEE !!}</p>
                </div>

                <!-- Bank Name Field -->
                <div class="form-group col-md-4">
                    {!! Form::label('BANK_NAME', 'Bank Name:') !!}
                    <p>{!! $provider->BANK_NAME !!}</p>
                </div>

                <!-- Cl Pay Acct No Field -->
                <div class="form-group col-md-4">
                    {!! Form::label('CL_PAY_ACCT_NO', 'Cl Pay Acct No:') !!}
                    <p>{!! $provider->CL_PAY_ACCT_NO !!}</p>
                </div>

                <!-- Bank Addr Field -->
                <div class="form-group col-md-4">
                    {!! Form::label('BANK_ADDR', 'Bank Addr:') !!}
                    <p>{!! $provider->BANK_ADDR !!}</p>
                </div>

                <!-- Created At Field -->
                <div class="form-group col-md-4">
                    {!! Form::label('created_at', 'Created At:') !!}
                    <p>{!! $provider->created_at !!}</p>
                </div>

                <!-- Updated At Field -->
                <div class="form-group col-md-4">
                    {!! Form::label('updated_at', 'Updated At:') !!}
                    <p>{!! $provider->updated_at !!}</p>
                </div>
            </div>
        </div>
    </div>
</div>
