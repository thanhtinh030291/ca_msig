<!-- Memb Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MEMB_NAME', 'Memb Name:') !!}
    {!! Form::text('MEMB_NAME', null, ['class' => 'form-control']) !!}
</div>

<!-- Pocy Ref No Field -->
<div class="form-group col-sm-6">
    {!! Form::label('POCY_REF_NO', 'Pocy Ref No:') !!}
    {!! Form::text('POCY_REF_NO', null, ['class' => 'form-control']) !!}
</div>

<!-- Memb Ref No Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MEMB_REF_NO', 'Memb Ref No:') !!}
    {!! Form::text('MEMB_REF_NO', null, ['class' => 'form-control']) !!}
</div>

<!-- Pres Amt Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PRES_AMT', 'Pres Amt:') !!}
    {!! Form::text('PRES_AMT', null, ['class' => 'form-control']) !!}
</div>

<!-- Inv No Field -->
<div class="form-group col-sm-6">
    {!! Form::label('INV_NO', 'Inv No:') !!}
    {!! Form::text('INV_NO', null, ['class' => 'form-control']) !!}
</div>

<!-- Prov Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PROV_NAME', 'Prov Name:') !!}
    {!! Form::text('PROV_NAME', null, ['class' => 'form-control']) !!}
</div>

<!-- Receive Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('RECEIVE_DATE', 'Receive Date:') !!}
    {!! Form::date('RECEIVE_DATE', null, ['class' => 'form-control','id'=>'RECEIVE_DATE']) !!}
</div>

@section('scripts')
    <script type="text/javascript">
        $('#RECEIVE_DATE').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: false
        })
    </script>
@endsection

<!-- Request Send Field -->
<div class="form-group col-sm-6">
    {!! Form::label('REQUEST_SEND', 'Request Send:') !!}
    {!! Form::text('REQUEST_SEND', null, ['class' => 'form-control']) !!}
</div>

<!-- Send Dlvn Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SEND_DLVN_DATE', 'Send Dlvn Date:') !!}
    {!! Form::date('SEND_DLVN_DATE', null, ['class' => 'form-control','id'=>'SEND_DLVN_DATE']) !!}
</div>

@section('scripts')
    <script type="text/javascript">
        $('#SEND_DLVN_DATE').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: false
        })
    </script>
@endsection

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('reportAdmins.index') !!}" class="btn btn-default">Cancel</a>
</div>
