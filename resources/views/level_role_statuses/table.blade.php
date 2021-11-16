<div class="table-responsive">
    <table class="table" id="levelRoleStatuses-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Claim Type</th>
        <th>Min Amount</th>
        <th>Max Amount</th>
        <th>Begin Status</th>
        <th>End Status</th>
        <th>Created At</th>
        <th>Updated At</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($levelRoleStatuses as $levelRoleStatus)
            <tr>
                <td>{!! $levelRoleStatus->name !!}</td>
                <td>{{ data_get(config('constants.claim_type'),$levelRoleStatus->claim_type) }}</td>
            <td>{!! formatPrice($levelRoleStatus->min_amount) !!}</td>
            <td>{!! formatPrice($levelRoleStatus->max_amount) !!}</td>
            <td>{!! data_get($list_status, $levelRoleStatus->begin_status) !!}</td>
            <td>{!! data_get($list_status, $levelRoleStatus->end_status) !!}</td>
            <td>{!! $levelRoleStatus->created_at !!}</td>
            <td>{!! $levelRoleStatus->updated_at !!}</td>
                <td>
                    {!! Form::open(['route' => ['levelRoleStatuses.destroy', $levelRoleStatus->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('levelRoleStatuses.show', [$levelRoleStatus->id]) !!}" class='btn btn-success btn-xs'><i class="fa fa-eye"></i></a>
                        <a href="{!! route('levelRoleStatuses.edit', [$levelRoleStatus->id]) !!}" class='btn btn-primary btn-xs'><i class="fa fa-pencil-square-o"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
