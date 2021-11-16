<div class="table-responsive">
    <table class="table" id="roleChangeStatuses-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Created User</th>
                <th>Updated User</th>
                <th>{{ __('message.date_created')}}</th>
                <th> {{__('message.date_updated')}}</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($roleChangeStatuses as $roleChangeStatus)
            <tr>
                <td>{!! $roleChangeStatus->name !!}</td>
                <td>{!! $roleChangeStatus->claim_type !!}</td>
                <td>{!! data_get($admin_list ,$roleChangeStatus->created_user) !!}</td>
                <td>{!! data_get($admin_list ,$roleChangeStatus->updated_user) !!}</td>
                <td>{!! $roleChangeStatus->created_at !!}</td>
                <td>{!! $roleChangeStatus->updated_at !!}</td>
                <td>
                    
                    {!! Form::open(['route' => ['roleChangeStatuses.destroy', $roleChangeStatus->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('roleChangeStatuses.show', [$roleChangeStatus->id]) !!}" class='btn btn-success btn-xs'><i class="fa fa-eye"></i></a>
                        <a href="{!! route('roleChangeStatuses.edit', [$roleChangeStatus->id]) !!}" class='btn btn-primary btn-xs'><i class="fa fa-pencil-square-o"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
