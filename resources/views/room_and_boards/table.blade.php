<div class="table-responsive">
    <table class="table" id="roomAndBoards-table">
        <thead>
            <tr>
                <th>Name</th>
        <th>Code Claim</th>
        <th>{{ __('message.date_created')}}</th>
        <th> {{__('message.date_updated')}}</th>
        <th>Created User</th>
        <th>Updated User</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($roomAndBoards as $roomAndBoard)
            <tr>
                <td>{!! $roomAndBoard->name !!}</td>
            <td>{!! $roomAndBoard->code_claim !!}</td>
            <td>{!! $roomAndBoard->created_at !!}</td>
            <td>{!! $roomAndBoard->updated_at !!}</td>
            <td>{!! $admin_list[$roomAndBoard->created_user] !!}</td>
            <td>{!! $admin_list[$roomAndBoard->updated_user] !!}</td>
                <td>
                    {!! Form::open(['route' => ['roomAndBoards.destroy', $roomAndBoard->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('roomAndBoards.show', [$roomAndBoard->id]) !!}" class='btn btn-success btn-xs'><i class="fa fa-eye"></i></a>
                        <a href="{!! route('roomAndBoards.edit', [$roomAndBoard->id]) !!}" class='btn btn-primary btn-xs'><i class="fa fa-pencil-square-o"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
