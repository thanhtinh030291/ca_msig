<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $roleChangeStatus->id !!}</p>
</div>

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    <p>{!! $roleChangeStatus->name !!}</p>
</div>


<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $roleChangeStatus->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $roleChangeStatus->updated_at !!}</p>
</div>

<!-- Created User Field -->
<div class="form-group">
    {!! Form::label('created_user', 'Created User:') !!}
    <p>{!! $roleChangeStatus->created_user !!}</p>
</div>

<!-- Updated User Field -->
<div class="form-group">
    {!! Form::label('updated_user', 'Updated User:') !!}
    <p>{!! $roleChangeStatus->updated_user !!}</p>
</div>

<!-- Is Deleted Field -->
<div class="form-group">
    {!! Form::label('is_deleted', 'Is Deleted:') !!}
    <p>{!! $roleChangeStatus->is_deleted !!}</p>
</div>

