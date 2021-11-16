<!-- Claim Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('claim_id', 'Claim Id:') !!}
    {!! Form::number('claim_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Mem Ref No Field -->
<div class="form-group col-sm-6">
    {!! Form::label('mem_ref_no', 'Mem Ref No:') !!}
    {!! Form::text('mem_ref_no', null, ['class' => 'form-control']) !!}
</div>

<!-- Visit Field -->
<div class="form-group col-sm-6">
    {!! Form::label('visit', 'Visit:') !!}
    {!! Form::text('visit', null, ['class' => 'form-control']) !!}
</div>

<!-- Assessment Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('assessment', 'Assessment:') !!}
    {!! Form::textarea('assessment', null, ['class' => 'form-control']) !!}
</div>

<!-- Medical Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('medical', 'Medical:') !!}
    {!! Form::textarea('medical', null, ['class' => 'form-control']) !!}
</div>

<!-- Claim Resuft Field -->
<div class="form-group col-sm-6">
    {!! Form::label('claim_resuft', 'Claim Resuft:') !!}
    {!! Form::text('claim_resuft', null, ['class' => 'form-control']) !!}
</div>

<!-- Note Field -->
<div class="form-group col-sm-6">
    {!! Form::label('note', 'Note:') !!}
    {!! Form::text('note', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('claimWordSheets.index') !!}" class="btn btn-default">Cancel</a>
</div>
