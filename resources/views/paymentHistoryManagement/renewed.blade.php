@extends('layouts.admin.master')
@section('title', 'Finance return to claim')
@section('stylesheets')
    <link href="{{ asset('css/condition_advance.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
    <link href="{{asset('plugins/datatables/dataTables.bootstrap4.min.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
    <style>
        table.dataTable thead>tr>th {
            font-size: 11px !important;
        }
    </style>
    @endsection
@section('content')
@include('layouts.admin.breadcrumb_index', [
    'title'       => 'Finance return to claim',
    'page_name'   => 'Finance return to claim',
])

<br>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <!-- Table Headings -->
                        <thead>
                            <tr>
                                <th>CL_NO</th>
                                <th>CL_TYPE</th>
                                <th >REASON........................................</th>
                                <th>MEMB_NAME</th>
                                <th>MEMB_REF_NO</th>
                                <th>POCY_REF_NO</th>
                                <th>PRES_AMT</th>
                                <th>APP_AMT</th>
                                <th>TF_AMT</th>
                                <th>created user</th>
                                <th>PAYMENT_METHOD</th>
                                <th>ACCT_NAME</th>
                                <th>ACCT_NO</th>
                                <th>BANK_NAME</th>
                                <th>BANK_CITY</th>
                                <th>BANK_BRANCH</th>
                                <th>BENEFICIARY_NAME</th>
                                <th>PP_DATE</th>
                                <th>PP_PLACE</th>
                                <th>PP_NO</th>
                            </tr>
                        </thead>
                        <!-- Table Body -->
                        
                        <tbody>
                            @foreach ($data as $value)
                            <tr>
                                <!-- ticket info -->
                                <td>{{ data_get($value,'CL_NO') }}</td>
                                <td>{{ data_get($value,'CL_TYPE') }}</td>
                                <td class="text-danger font-weight-bold">{{ data_get($value,'REASON') }}</td>
                                <td>{{ data_get($value,'MEMB_NAME') }}</td>
                                <td>{{ data_get($value,'MEMB_REF_NO') }}</td>
                                <td>{{ data_get($value,'POCY_REF_NO') }}</td>
                                <td class="text-danger font-weight-bold">{{ formatPrice(data_get($value,'PRES_AMT',0)) }}</td>
                                <td class="text-danger font-weight-bold">{{ formatPrice(data_get($value,'APP_AMT',0)) }}</td>
                                <td class="text-danger font-weight-bold">{{ formatPrice(data_get($value,'TF_AMT',0)) }}</td>
                                <td >{{ data_get($value,'CL_USER') }}</td>
                                <td>{{ data_get($value,'PAYMENT_METHOD') }}</td>
                                <td>{{ data_get($value,'ACCT_NAME') }}</td>
                                <td>{{ data_get($value,'ACCT_NO') }}</td>
                                <td>{{ data_get($value,'BANK_NAME') }}</td>
                                <td>{{ data_get($value,'BANK_CITY') }}</td>
                                <td>{{ data_get($value,'BANK_BRANCH') }}</td>
                                <td>{{ data_get($value,'BENEFICIARY_NAME') }}</td>
                                <td>{{ data_get($value,'PP_DATE') }}</td>
                                <td>{{ data_get($value,'PP_PLACE') }}</td>
                                <td>{{ data_get($value,'PP_NO') }}</td>
                                
                            </tr>
                            @endforeach
                        </tbody>
                        
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.admin.partials.delete_model', [
    'title'           => __('message.product_warning'),
    'confirm_message' => __('message.product_confirm'),
])

@endsection
@section('scripts')
<script src="{{asset('js/lengthchange.js?vision=') .$vision }}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js?vision=') .$vision }}" ></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap4.min.js?vision=') .$vision }}" ></script>
<script>
    $( document ).ready( function() {
        var table = $('.table').DataTable();
        var html = $( 'table thead tr' ).clone( true )[0];
        $( 'table thead' ).append(html);
        $( 'table thead tr:eq(1) th' ).each( function (i) {
            if ( i == 0 ) {
                $( this ).html( '' );
            } else {
                var title = $( this ).text();
                $( this ).html( '<input type="text" class="form-control">' );
            }
    
            $( 'input', this ).on( 'keyup change', function () {
                if ( table.column( i ).search() !== this.value ) {
                    table
                        .column( i )
                        .search( this.value )
                        .draw();
                }
            } );
        } );
        
        
    } );
</script>
@endsection
