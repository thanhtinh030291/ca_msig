@php
$langs = config('constants.lang');
$min = config('constants.minMaxLength.min');
$max = config('constants.minMaxLength.max');
@endphp
@extends('layouts.admin.master')
@section('title', 'Message')
@section('stylesheets')
<link rel="stylesheet" type="text/css" href="{{asset('css/jquery-ui.min.css?vision=') .$vision }}">
<link href="{{ asset('css/multi_lang.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css"/>
<link href="{{ asset('css/chat.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
<link href="{{ asset('css/icheck.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
@include('layouts.admin.breadcrumb_index', [
    'title'       => 'Messagebox' ,
    'parent_url'  => route('home'),
    'parent_name' => "Home",
    'page_name'   => 'Messagebox',
])
<section class="content">
    <div class="row">
    <div class="col-md-3">
        @include('mailBoxManagement.menuRight')
    </div>
    <!-- /.col -->
    <div class="col-md-9">
        <div class="box box-primary">
        <div class="box-header with-border">
            
            {{ Form::open(array('url' => route('mailbox.index'), 'method' => 'get', 'class' => 'form-inline')) }}
                <div class="form-group mx-sm-3 mb-2">
                    <label for="inputPassword2" class="sr-only">text</label>
                    {{Form::text('search', data_get($search_params, 'search'), ['class' => 'form-control', 'placeholder'=>"Search"])}}
                </div>
                <button type="submit" class="btn btn-primary mb-2"><i class="fa fa-search" aria-hidden="true"></i></button>
            {{ Form::close() }}
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body no-padding">

            <div class="table-responsive mailbox-messages">
                @if (count($mess) > 0)
                {{ $mess->links() }}
                @endif
            <table class="table table-hover table-striped table-datatable">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>email_to</th>
                        <th>message</th>
                        <th>time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mess as $item)
                    <tr>
                        <td>
                            <input type="checkbox" name="quux[]" data-id = {{$item->getUid()}} class="icheck" >
                            {{$item->getUid()}}
                        </td>
                        <td class="mailbox-star" data-id = {{$item->getUid()}}>
                            <a href="javascript:;">
                                @if($item->important == 1 )
                                    <i class="fa fa-star text-yellow"></i>
                                @else
                                    <i class="fa fa-star-o text-yellow"></i>
                                @endif
                            </a>
                        </td>
                        <td class="mailbox-name"><a href="{{url("admin/mailbox")."/".$item->getUid()}}">{{$item->getTo()[0]->mail}}</a></td>
                        <td class="mailbox-subject">
                            <p>{!! truncate(strip_tags($item->getSubject()) , 150)!!}</p>
                        </td>
                        <td class="mailbox-subject">
                            <p>{!! $item->date[0]!!}</p>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if (count($mess) > 0)
                {{ $mess->links() }}
            @endif
            <!-- /.table -->
            </div>
            <!-- /.mail-box-messages -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer no-padding">
            
        </div>
        </div>
        <!-- /. box -->
    </div>
    <!-- /.col -->
    </div>
    <!-- /.row -->
</section>

@endsection
@section('scripts')

<script src="{{ asset('js/tinymce.js?vision=') .$vision }}"></script>
<script src="{{ asset('js/icheck.min.js?vision=') .$vision }}"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var arr_icheck = [];
    $(function () {
        //Enable iCheck plugin for checkboxes
        //iCheck for checkbox and radio inputs
        $('.mailbox-messages input[type="checkbox"]').iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass: 'iradio_flat-blue'
        });
        //listen check uncheck
        $('input').on('ifChecked', function(event){
            var id = $(this).attr("data-id");
            arr_icheck.push(id);
            $("#arr_delete").val(arr_icheck.join(","));
        });
        $('input').on('ifUnchecked', function(event){
            var id = $(this).attr("data-id");
            var index = arr_icheck.indexOf(id);
            if (index > -1) {
                arr_icheck.splice(index, 1);
            }
            $("#arr_delete").val(arr_icheck.join(","));
        });


        //Enable check and uncheck all functionality
        $(".checkbox-toggle").click(function () {
            var clicks = $(this).data('clicks');

            if (clicks) {
                //Uncheck all checkboxes
                $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
                $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
            } else {
                //Check all checkboxes
                $(".mailbox-messages input[type='checkbox']").iCheck("check");
                $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
            }
            $(this).data("clicks", !clicks);
        });

        //Handle starring for glyphicon and font awesome
        $(".mailbox-star").click(function (e) {
        e.preventDefault();
        //detect type
        var $this = $(this).find("a > i");
        var glyph = $this.hasClass("glyphicon");
        var fa = $this.hasClass("fa");

        //Switch states
        if (glyph) {
            $this.toggleClass("glyphicon-star");
            $this.toggleClass("glyphicon-star-empty");
        }

        if (fa) {
            $this.toggleClass("fa-star");
            $this.toggleClass("fa-star-o");
            var id = $(this).attr("data-id");
            var value = 0; 
            if($this.hasClass('fa-star-o')){
                value = 0 ;
            }else{
                value = 1;
            }
            axios.post("{{route('message.important')}}",{
            'id' : id ,
            'value' : value,
            })
            .then(function (response) {
                $(".loader").fadeOut("slow");
                $.notify({
                    icon: 'fa fa-bell',
                    title: '<strong>Hệ THống</strong>',
                    message: "Gửi Thành Công"
                },{
                    placement: {
                        from: "top",
                        align: "right"
                    },
                    type: 'success'
                });
            })
            .catch(function (error) {
                $(".loader").fadeOut("slow");
                alert(error);
                
            });
        }
        });
    });
    $(document).ready( function () {
        
    });
</script>
@endsection
