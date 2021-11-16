<div id="csrModal" class="modal fade bd-example-modal-lg" role="dialog">
    <div class="modal-dialog modal-lg">
        
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">CSR FILE SHOWS</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                    <div class="row  mb-2">
                        <table  class="table table-striped header-fixed col-md-12">
                            <thead>
                                <tr>
                                    <th>fileName</th>
                                    <th>member number</th>
                                    <th>provider name</th>
                                    <th>Claim No.</th>
                                    <th>Update By</th>
                                    <th>Update At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($CsrFile)
                                    @foreach ($CsrFile as $item)
                                        <tr>
                                            <td>{{$item->filename}}</td>
                                            <td>{{explode("|", $item->idx_val)[0]}}</td>
                                            <td>{{explode("|", $item->idx_val)[2]}}</td>
                                            <td>{{explode("|", $item->idx_val)[4]}}</td>
                                            <td>{{$item->upd_user}}</td>
                                            <td>{{$item->upd_date}}</td>
                                            <td>
                                                <a href="{{config('constants.mount_dlvn'). $item->path . $item->filename}}" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a>
                                                {{ Form::open(array('url' => '/admin/claim/sendCSRFile/'.$data->id, 'method'=>'post', 'files' => true, 'style' => 'width: 100%;'))}}
                                                    <div class="row">
                                                        {{ Form::hidden('rpid_oid',  $item->rpid_oid ) }}
                                                        <button class=""><i class="fa fa-print col-md-4" aria-hidden="true"></i>Lưu vào tệp đã sắp xếp</button>
                                                    </div>
                                                {{ Form::close() }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div id = 'button_save' class="pull-right">
                            <button type="button" class="btn btn-secondary btn-cancel-delete" 
                                data-dismiss="modal">Close</button>
                        </div><br>
                    </div>

            </div>
        </div>
    </div>
</div>