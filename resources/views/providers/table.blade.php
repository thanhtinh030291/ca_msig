<div class="table-responsive">
    <table class="table" id="providers-table">
        <thead>
            <tr>
                <th>Prov Code</th>
                <th>Prov Name</th>
                <th>Balance</th>
                <th>Addr</th>
                <th>Scma Oid Country</th>
                <th>Payee</th>
                <th>Bank Name</th>
                <th>Cl Pay Acct No</th>
                <th>Bank Addr</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($providers as $provider)
            <tr>
                <td>{!! $provider->PROV_CODE !!}</td>
                <td>{!! $provider->PROV_NAME !!}</td>
                <td class="text-danger font-weight-bold">{!! formatPrice($provider->TotalDeduct) !!}</td>
                <td>{!! $provider->ADDR !!}</td>
                <td>{!! $provider->SCMA_OID_COUNTRY !!}</td>
                <td>{!! $provider->PAYEE !!}</td>
                <td>{!! $provider->BANK_NAME !!}</td>
                <td>{!! $provider->CL_PAY_ACCT_NO !!}</td>
                <td>{!! $provider->BANK_ADDR !!}</td>
                <td>
                    <div class='btn-group'>
                        <a href="{!! route('providers.show', [$provider->id]) !!}" class='btn btn-success btn-xs'><i class="fa fa-eye"></i></a>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
