<div class="table-responsive">
    <table class="table" id="groupUsers-table">
        <thead>
            <tr>
                <th>Id</th>
        <th>Name</th>
        <th>Lead</th>
        <th>Supper</th>
        <th>Assistant Manager</th>
        <th>Manager</th>
        <th>Header</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($groupUsers as $groupUser)
            <tr>
            <td>{!! $groupUser->id !!}</td>
            <td>{!! $groupUser->name !!}</td>
            <td>{!! data_get($admin_list,$groupUser->lead) !!}</td>
            <td>{!! data_get($admin_list,$groupUser->supper) !!}</td>
            <td>{!! data_get($admin_list,$groupUser->assistant_manager) !!}</td>
            <td>{!! data_get($admin_list,$groupUser->manager) !!}</td>
            <td>{!! data_get($admin_list,$groupUser->header) !!}</td>
                <td>
                    {!! Form::open(['route' => ['groupUsers.destroy', $groupUser->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('groupUsers.show', [$groupUser->id]) !!}" class='btn btn-success btn-xs'><i class="fa fa-eye"></i></a>
                        <a href="{!! route('groupUsers.edit', [$groupUser->id]) !!}" class='btn btn-primary btn-xs'><i class="fa fa-pencil-square-o"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
