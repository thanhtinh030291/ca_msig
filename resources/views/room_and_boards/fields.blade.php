<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Code Claim Field -->
<div class="form-group col-sm-6">
    {!! Form::label('code_claim', 'Code Claim:') !!}
    {!! Form::select('code_claim', ['' => ''], null, ['class' => 'form-control']) !!}
</div>

<!-- Time Start Field -->
<div class="form-group col-sm-6">
    {!! Form::label('time_start', 'Time Start:') !!}
    {!! Form::text('time_start', null, ['class' => 'form-control']) !!}
</div>

<!-- Time End Field -->
<div class="form-group col-sm-6">
    {!! Form::label('time_end', 'Time End:') !!}
    {!! Form::text('time_end', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('roomAndBoards.index') !!}" class="btn btn-default">Cancel</a>
</div>
