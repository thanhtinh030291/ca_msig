@extends('layouts.admin.master')
@section('title', 'reportGop')
@section('stylesheets')
    <link href="{{ asset('css/fileinput.css') }}" media="all" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-ui.min.css') }}">
    <link href="{{ asset('css/setting_date.css') }}" media="all" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('plugins/datatables/jquery.dataTables.min.css') }}" media="all" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    @include('layouts.admin.breadcrumb_index', [
        'title'       => 'reportGop',
        'parent_url'  =>  route('reportGop.index'),
        'parent_name' => 'reportGop',
        'page_name'   => 'reportGop',
    ])
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @include('report_gop.show_fields')
                    <a href="{!! route('reportGop.index') !!}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script src="{{asset('js/datatables.min.js')}}"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $( document ).ready( function() {
        var list_selected_index = [];
        
        
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
        var table = $( 'table' ).DataTable( {
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
            order: [[ 1, "desc" ]],
            dom: "<'row'<'col-12'B>>" +
                "<'row'<'col-6'l><'col-6'f>>" +
                "<'row'<'col-12'tr>>" +
                "<'row'<'col-5'i><'col-7'p>>",
            buttons: [
                {
                    extend: 'excel',
                    filename: 'report',
                    className: 'btn btn-excel',
                    title : null,
                    exportOptions: {
                        columns: ':visible:not(.not-export-col)'
                    }
                },
                'selectAll',
                'selectNone'
            ],
            columnDefs: [
                {
                    targets: [ ],
                    visible: false
                }
            ],
            select: {
                style: 'multi'
            },
            fixedHeader: true,
            orderCellsTop: true,
        } );
        table.on( 'select deselect', function ( e, dt, type, indexes ) {
            var rowData = table.rows( { selected: true } ).data();
            var sumAmt = 0;
            $.each( rowData, function( key, value ) {
                sumAmt += parseInt(value[3].replace( /,/g, '' ));
            });
            sumAmt = sumAmt.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
            $( '.select_amt' ).html( sumAmt );

            if ( e.type == 'select' ) {
                list_selected_index.push( indexes[0] );
            } else if ( e.type == 'deselect' ) {
                list_selected_index = list_selected_index.filter( function( elem ) {
                    return elem != indexes[0];
                } );
            }
        } );
    } );
</script>

@endsection
