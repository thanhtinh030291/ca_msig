<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#{{isset($idmodal) ? $idmodal : "exmodal" }}">Open History</button>
<div class="modal fade" id="{{isset($idmodal) ? $idmodal : "exmodal" }}" role="dialog">
<div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Event</th>
                <th> 
                    <div class="row">
                        <div class="col-md-6 bg-primary">New</div>
                        <div class="col-md-6 bg-warning">Old</div>
                    </div>
                </th> 
                <th>User</th>
                <th>Time</th>
            </tr>
            </thead>
            <tbody>
            @if(!empty($log_history))
                @foreach ($log_history as $key => $item)
                <tr>
                    <td>{{$item->description}}</td>
                    @if($item->description == 'updated')
                        <td>
                            
                            @foreach ($item->properties['attributes'] as $keyatt => $valueatt)
                                @if($valueatt != $item->properties['old'][$keyatt])
                                    <div class="row">
                                        @if (!is_array($valueatt))
                                        <div class="col-md-6 bg-primary">{!!$keyatt .":" .$valueatt!!}</div>
                                        <div class="col-md-6 bg-warning">{!!$keyatt .":" .data_get($item->properties,"old.".$keyatt)!!}</div>
                                        @else
                                            <div class="col-md-6 bg-primary">{!!$keyatt .":" .json_encode($valueatt,true)!!}</div>
                                            <div class="col-md-6 bg-warning">{!!$keyatt .":" .json_encode($item->properties['old'][$keyatt],true)!!}</div>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                            
                        </td>
                    @else
                        <td></td>
                    @endif
                    <td>{{data_get($listUser->toArray(), $item->causer_id)}}</td>
                    <td>{{$item->created_at}}</td>
                </tr>   
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
    </div>
    
</div>
</div>
