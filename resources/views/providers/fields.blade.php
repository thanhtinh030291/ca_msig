

<div class="row">
    <!-- Prov Code Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('PROV_CODE', 'Prov Code:') !!}
        {!! Form::text('PROV_CODE', null, ['class' => 'form-control', 'required','readonly']) !!}
    </div>

    <!-- Eff Date Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('EFF_DATE', 'Eff Date:') !!}
        {!! Form::text('EFF_DATE', null, ['class' => 'form-control', 'readonly']) !!}
    </div>

    <!-- Term Date Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('TERM_DATE', 'Term Date:') !!}
        {!! Form::text('TERM_DATE', null, ['class' => 'form-control', 'readonly']) !!}
    </div>

    <!-- Prov Name Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('PROV_NAME', 'Prov Name:') !!}
        {!! Form::text('PROV_NAME', null, ['class' => 'form-control', 'readonly']) !!}
    </div>

    <!-- Addr Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('ADDR', 'Addr:') !!}
        {!! Form::text('ADDR', null, ['class' => 'form-control', 'readonly']) !!}
    </div>

    <!-- Scma Oid Country Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('SCMA_OID_COUNTRY', 'Scma Oid Country:') !!}
        {!! Form::text('SCMA_OID_COUNTRY', null, ['class' => 'form-control', 'readonly']) !!}
    </div>

    <!-- Payee Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('PAYEE', 'Payee:') !!}
        {!! Form::text('PAYEE', null, ['class' => 'form-control', 'readonly']) !!}
    </div>

    <!-- Bank Name Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('BANK_NAME', 'Bank Name:') !!}
        {!! Form::text('BANK_NAME', null, ['class' => 'form-control', 'readonly']) !!}
    </div>

    <!-- Cl Pay Acct No Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('CL_PAY_ACCT_NO', 'Cl Pay Acct No:') !!}
        {!! Form::text('CL_PAY_ACCT_NO', null, ['class' => 'form-control', 'readonly']) !!}
    </div>

    <!-- Bank Addr Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('BANK_ADDR', 'Bank Addr:') !!}
        {!! Form::text('BANK_ADDR', null, ['class' => 'form-control', 'readonly']) !!}
    </div>

    <!-- Submit Field -->
    <div class="form-group col-sm-12">
        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
        <a href="{!! route('providers.index') !!}" class="btn btn-default">Cancel</a>
    </div>
</div>
