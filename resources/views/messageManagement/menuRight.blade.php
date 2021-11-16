<a href="compose.html" class="btn btn-primary btn-block margin-bottom">Compose</a>

        <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Folders</h3>
        </div>
        <div class="box-body p-0">
            <ul class="nav nav-pills nav-stacked">
            <li><a href="{{route('message.index')}}"><i class="fa fa-inbox"></i> Inbox
                <span class="label label-primary pull-right">12</span></a></li>
            <li><a href="{{route('message.sent')}}"><i class="fa fa-envelope-o"></i> Sent</a></li>
            <li><a href="{{route('message.trash')}}"><i class="fa fa-trash-o"></i> Trash</a></li>
            </ul>
        </div>
        <!-- /.box-body -->
        </div>
        <!-- /. box -->
        <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Labels</h3>
        </div>
        <div class="box-body p-0" >
            <ul class="nav nav-pills nav-stacked">
                <li>
                    {{ Form::open(array('url' => route('message.index'), 'method' => 'get', 'class' => 'form-inline')) }}
                        
                        <button type="submit" name="important" value="1" class=""><i class="fa fa-star text-yellow"></i> Important</button>
                    {{ Form::close() }}
                </li>
            </ul>
        </div>
        <!-- /.box-body -->
        </div>
        <!-- /.box -->