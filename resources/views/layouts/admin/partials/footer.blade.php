<!-- Stored in resources/views/layouts/admin/partials/dashboa著作権rd.blade.php -->

<footer class="footer">
    <span class="text-right">
    {{ __('message.copyright') . __('message.cat_next_string') }}<a target="_blank" href="#">{{ config('app.name') }}</a>
    </span>
    <div id="cmess">
        <a href="javascript:;" class="chat_fb" onclick="javascript:fchat();"><i class="fa fa-comments"></i> Message Box</a>
        <div id="fchat" class="fchat" style="display: none;">
            <div class="container">
                <form action="{{route('postMessage')}}" method="post">
                    @csrf
                    {{ Form::label('user', 'Email', array('class' => 'labelas')) }}
                    {{ Form::select('user_f', $listUser, old('user_f'), array( 'id' => 'user_f' ,'class' => 'select2 form-control', 'required')) }}

                    <div class="btn-group" role="group" aria-label="Basic example">
                        {{ Form::label('Content', 'Content', array('class' => 'labelas')) }}
                        <button type="button" class="btn btn-secondary" onclick="contentReject()">Từ chối</button>
                        <button type="button" class="btn btn-secondary" onclick="contentAccept()">Đồng ý</button>
                        <button type="button" class="btn btn-secondary" onclick="contentRequest()">Yêu cầu</button>
                    </div>
                    
                    {{ Form::textarea('content_f', null, array('id' => 'content_f', 'class' => 'form-control', 'placeholder' => "Write something..", 'style' => "height:140px")) }}
                    <input type="button" class='btn btn-primary' value="Submit" onclick="postMessagee(this)">
                </form>
            </div>
        </div>
        <input type="hidden" id="tchat" value="1">
    </div>
</footer>
