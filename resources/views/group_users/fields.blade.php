<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<table class="table">
    <thead class="thead-dark">
        <tr>
            <th scope="col">Lead</th>
            <th scope="col">Supper</th>
            <th scope="col">Assistant Manager</th>
            <th scope="col">Manager</th>
            <th scope="col">Header</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <label class="checkbox-inline">
                {!! Form::hidden('active_leader', 0) !!}
                {!! Form::checkbox('active_leader', '1', null) !!}
                </label>
            </td>
            <td>
                <label class="checkbox-inline">
                    {!! Form::hidden('active_supper', 0) !!}
                    {!! Form::checkbox('active_supper', '1', null) !!}
                </label>
            </td>
            <td>
                <label class="checkbox-inline">
                    {!! Form::hidden('active_assistant_manager', 0) !!}
                    {!! Form::checkbox('active_assistant_manager', '1', null) !!}
                </label>
            </td>
            <td>
                <label class="checkbox-inline">
                    {!! Form::hidden('active_manager', 0) !!}
                    {!! Form::checkbox('active_manager', '1', null) !!}
                </label>
            </td>
            <td>
                <label class="checkbox-inline">
                    {!! Form::hidden('active_header', 0) !!}
                    {!! Form::checkbox('active_header', '1', null) !!}
                </label>
            </td>
        </tr>
        <tr>
            <td>{!! Form::select('lead',$list_user, null, ['class' => 'form-control select2',"placeholder"=>"---"]) !!}</td>
            <td>{!! Form::select('supper',$list_user, null, ['class' => 'form-control select2',"placeholder"=>"---"]) !!}</td>
            <td>{!! Form::select('assistant_manager',$list_user, null, ['class' => 'form-control select2',"placeholder"=>"---"]) !!}</td>
            <td>{!! Form::select('manager',$list_user, null, ['class' => 'form-control select2',"placeholder"=>"---"]) !!}</td>
            <td>{!! Form::select('header',$list_user, null, ['class' => 'form-control select2',"placeholder"=>"---"]) !!}</td>
        </tr>
    </tbody>
</table>
<table class="table">
    <thead class="thead-dark">
        <tr>
            <th scope="col">User oF Group</th>
            <th scope="col">All user</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <ul id="sortable1" class="sortable_list connectedSortable list-group bg-info" style="height: 450px ; width:100%;  overflow: scroll; overflow-x: hidden;">
                    @foreach ($list_user_of_group as $item)
                    <li class="ui-state-highlight list-group-item " id="{{ $item->id }}">{{ $item->name }}</li>
                    @endforeach
                </ul>
            </td>
            <td>
                <ul id="sortable2" class="sortable_list connectedSortable list-group"  style="height: 450px ; width:100%;  overflow: scroll; overflow-x: hidden;">
                    @foreach ($list_user_non_group as $item)
                    <li class="ui-state-highlight list-group-item" id="{{ $item->id }}">{{ $item->name }}</li>
                    @endforeach
                </ul>
            </td>
        </tr>
    </tbody>
</table>
{!! Form::hidden('_user', null , ["id"=> "group_user"]) !!}



<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('groupUsers.index') !!}" class="btn btn-default">Cancel</a>
</div>

