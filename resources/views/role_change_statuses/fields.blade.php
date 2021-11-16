<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-6">
    {{ Form::label('claim_type', __('message.claim_type')) }}
    {{ Form::select('claim_type', config('constants.claim_type'),old('claim_type'), ['id' => 'claim_type', 'class' => 'form-control ']) }}<br>
<div class="form-group col-sm-6">
<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('roleChangeStatuses.index') !!}" class="btn btn-default">Cancel</a>
</div>
