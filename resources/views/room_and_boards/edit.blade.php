@extends('layouts.admin.master')
@section('title', 'Room And Board')
@section('stylesheets')
<link rel="stylesheet" type="text/css" href="{{asset('css/jquery-ui.min.css?vision=') .$vision }}">
@endsection
@section('content')
    @include('layouts.admin.breadcrumb_index', [
        'title'       => 'Room And Board',
        'parent_url'  => route('roomAndBoards.index'),
        'parent_name' => 'Room And Boards',
        'page_name'   =>  'Room And Board',
    ])
    {{ Form::open(array('url' => "admin/roomAndBoards/{$roomAndBoard->id}", 'method' => 'post' ,'class'=>'form-horizontal')) }}
    @method('PUT')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                        <div class="form-group col-md-12">
                            {!! Form::label('name', 'Name:') !!}<span class="text-danger">*</span>
                            {!! Form::text('name', $roomAndBoard->name, ['class' => 'form-control', 'required']) !!}
                        </div>
                        
                        <!-- Code Claim Field -->
                        <div class="form-group col-md-12">
                            {!! Form::label('code_claim', 'Code Claim:') !!}
                            {{ Form::select('code_claim', $listCodeClaim, $roomAndBoard->code_claim, array('id'=>'code_claim', 'class' => 'code_claim form-control', 'required')) }}
                        </div>

                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <h5 class="card-header">Applicant Information</h5>
                <div class="card-body" id="result_applicant">
                    
                </div>
            </div>
        </div>
    </div>
    {{-- // cl line --}}
    <div class="row mt-3">
        <div class="card table-responsive col-md-12"  style="max-height:450px">
            <table id="season_price_tbl" class="table table-striped header-fixed">
                <thead>
                    <tr>
                        <th>Claim Line No.</th>
                        <th>Prov name</th>
                        <th>Pres amt</th>
                        <th>Incur date</th>
                        <th>Info</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="empty_item" style="display: none;">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr id="clone_item" style="display: none;">
                        <td style="width:100px">{{ Form::text('_line_no_default', null, ['class' => 'form-control p-1 ','readonly' , 'disabled']) }}</td>
                        <td style="width:330px">
                            {{ Form::text('_prov_name_default', null, ['class' => 'item-price form-control p-1', 'readonly' , 'disabled']) }}
                        </td>
                        <td style="width:150px">
                            {{ Form::text('_pres_amt_default', null, ['class' => 'item-price form-control p-1', 'readonly' , 'disabled']) }}
                        </td>
                        <td style="width:310px">
                            <div style="width:310px">
                                {!! Form::text('_incur_date_default', null ,  ['class' => 'datetimepicker form-control' ,'placeholder' => 'Not Reject' , 'required']) !!}
                            </div>
                        </td>
                        <td>
                            <button class="btn btn-success" id="btn_check_default" type="button" onclick="checkRoomBoard(this);">Check</button>
                            <div class="row" id ="template_default" >None</div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- Submit Field -->
            <div class="form-group col-sm-12">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{!! route('roomAndBoards.index') !!}" class="btn btn-default">Cancel</a>
            </div>                        
            
        </div>
    </div>
    {!! Form::close() !!}
    {{-- // template info --}}
    <div id="template_info" class="row" style="display:none">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Policy Plan</h5>
                
                    <p class="card-text">Amt/Day: $amt_day</p>
                    <p class="card-text">Amt/Hours: $amt_hour</p>
                    <p class="card-text">Day/Dis/Yr: $day_dis_yr</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Results after check</h5>
                    <p class="card-text">Total Hours At Room&Board: $total_hour</p>
                    <p class="card-text">Approve Amount (Max): $approve_max</p>
                    <p class="card-text">Approve Amount: $approve_amount</p>
                    <p class="card-text" style ="display:none">Reject Amount: $reject_amount</p>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
<script src="{{ asset('js/format-price.js?vision=') .$vision }}"></script>
<script src="{{ asset('js/roomboard.js?vision=') .$vision }}"></script>
<script type="text/javascript">
    function addHourOld(){
        var  arrHour= @json($roomAndBoard->line_rb);
        $.each(arrHour, function( index, value ) {
            var i = index + 1;
            var hours_start = value.hours_start;
            var hours_end = value.hours_end;
            var value_res = $('input[name="_incur_date['+i+']"]').val();
            value_res = value_res.replace(/([01]?[0-9]|2[0-3]):[0-5][0-9]/, hours_start);
            value_res = value_res.replace(/([01]?[0-9]|2[0-3]):[0-5][0-9]+$/, hours_end);
            $('input[name="_incur_date['+i+']"]').val(value_res);
        });
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
@endsection
