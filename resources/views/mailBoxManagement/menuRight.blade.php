<a href="compose.html" class="btn btn-primary btn-block margin-bottom">Compose</a>

        <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Folders</h3>
        </div>
        <div class="box-body p-0">
            <ul class="nav nav-pills nav-stacked">
                
                <li>
                    <a href="/admin/mailbox"><i class="fa fa-folder-o">SENT MESSAGES
                        </i> <span class="label label-primary pull-right"></span>
                    </a>
                </li>  
                <li>
                    <a href="/admin/mailbox-error-messages"><i class="fa fa-folder-o">ERROR MESSAGES
                        </i> <span class="label label-primary pull-right"></span>
                    </a>
                </li>  
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