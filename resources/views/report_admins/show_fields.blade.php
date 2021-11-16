<div class="card-body table-responsive">
    <table class="table table-hover table-striped table-bordered">
        <thead>
            <tr class="bg-info text-white">
                <th scope="col" class="noVis">STT</th>
                <th scope="col" class="noVis">Finish</th>
                <th scope="col" class="noVis">Link (ID Etalk)</th>
                <th scope="col" class="noVis">Claimant</th>
                <th scope="col" class="noVis">Product</th>
                <th scope="col" class="noVis">CL No</th>
                <th scope="col" class="noVis">Policy No.</th>
                <th scope="col" class="noVis">Client ID.</th>
                <th scope="col" class="noVis">Invoice No.</th>
                <th scope="col" class="noVis">Claim (VND)</th>
                <th scope="col" class="noVis">Provider's Name</th>
                <th scope="col" class="noVis">Receiver</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reportAdmin as $key => $item)
            <tr>
                <td>{!! $key + 1 !!}</td>
                <td>{!! $item->REQUEST_SEND == 0 ? '<div class="p-3 mb-2 bg-danger text-white">No</div>' : '<div class="p-3 mb-2 bg-success text-white">Yes</div>' !!}</td>
                <td><a target="_blank" rel="noopener" href="{!! url('/admin/claim/'.$item->claim_id) !!}">Link Etalk "{{$item->barcode}}"</a></td>
                <td>{!! $item->MEMB_NAME !!}</td>
                <td>CATHAY</td>	
                <td>{!! $item->CL_NO !!}</td>
                <td>{!! $item->POCY_REF_NO !!}</td>
                <td>{!! $item->MEMB_REF_NO !!}</td>
                <td>{!! $item->INV_NO !!}</td>
                <td>{!! $item->PRES_AMT !!}</td>
                <td>{!! $item->PROV_NAME !!}</td>
                <td>{!! data_get($admin_list,$item->created_user) !!}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>