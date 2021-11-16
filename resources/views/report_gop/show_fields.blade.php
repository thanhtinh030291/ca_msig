<div class="card-body table-responsive">
    <table class="table table-hover table-striped table-bordered">
        <thead>
            <tr class="bg-info text-white">
                <th scope="col" class="noVis">STT</th>
                <th scope="col" class="noVis">cl no</th>
                <th scope="col" class="noVis">tf_amt</th>
                <th scope="col" class="noVis">mantis_id</th>
                <th scope="col" class="noVis">cl_type</th>
                <th scope="col">$payment_method</th>
                <th scope="col">acct_name</th>
                <th scope="col">acct_no</th>
                <th scope="col">bank_name</th>
                <th scope="col">bank_branch</th>
                <th scope="col">bank_city</th>
                <th scope="col">beneficiary_name</th>
                <th scope="col">pp_no</th>
                <th scope="col">pp_date</th>
                <th scope="col">pp_place</th>
                <th scope="col">pocy_ref_no</th>
                <th scope="col">memb_ref_no</th>
                <th scope="col">memb_name</th>
                <th scope="col">prov_name</th>
                <th scope="col">payee</th>
                <th scope="col">inv_no</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reportAdmin as $key => $payment)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $payment->CL_NO }}</td>
                <td class="text-right">{{ $payment->TF_AMT }}</td>
                <td class="text-right">{{ $payment->MANTIS_ID }}</td>
                <td>{{ $payment->CL_TYPE }}</td>
                <td>{{ $payment->PAY_METHOD }}</td>
                <td>{{ $payment->ACCT_NAME }}</td>
                <td>{{ $payment->ACCT_NO }}</td>
                <td>{{ $payment->BANK_NAME }}</td>
                <td>{{ $payment->BANK_BRANCH }}</td>
                <td>{{ $payment->BANK_CITY }}</td>
                <td>{{ $payment->BENEFICIARY_NAME }}</td>
                <td>{{ $payment->PP_NO }}</td>
                <td>{{ $payment->PP_DATE }}</td>
                <td>{{ $payment->PP_PLACE }}</td>
                <td>{{ $payment->POCY_REF_NO }}</td>
                <td>{{ $payment->MEMB_REF_NO }}</td>
                <td>{{ $payment->MEMB_NAME }}</td>
                <td>{{ $payment->PROV_NAME }}</td>
                <td>{{ $payment->PAYEE }}</td>
                <td>{{ $payment->INV_NO }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>