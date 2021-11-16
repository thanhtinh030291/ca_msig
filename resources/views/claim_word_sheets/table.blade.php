<div class="table-responsive">
    <table class="table" id="claimWordSheets-table">
        <thead>
            <tr>
                <th>Claim code</th>
                <th>Mem Ref No</th>
                <th>Claim Resuft</th>
                <th>Created By User</th>
                <th>Updated By User</th>
                <th>Updated At</th>
                <th>Status</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($claimWordSheets as $claimWordSheet)
            <tr>
                <td>{!! isset($claimWordSheet->claim->code_claim_show) ? $claimWordSheet->claim->code_claim_show : " " !!}</td>
                <td>{!! $claimWordSheet->mem_ref_no !!}</td>
                <td>{!! $claimWordSheet->claim_resuft ? data_get(config('constants.claim_result'), $claimWordSheet->claim_resuft ) : "" !!}</td>
                <td>{!! data_get($admin_list, $claimWordSheet->created_user) !!}</td>
                <td>{!! data_get($admin_list, $claimWordSheet->updated_user) !!}</td>
                <td>{!! $claimWordSheet->updated_at !!}</td>
                <td>{!! data_get(config('constants.statusWorksheet'),$claimWordSheet->status) !!}</td>
                <td>
                    {!! Form::open(['route' => ['claimWordSheets.destroy', $claimWordSheet->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('claimWordSheets.show', [$claimWordSheet->id]) !!}" class='btn btn-success btn-xs'><i class="fa fa-eye"></i></a>
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
