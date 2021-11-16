<div id="deleteConfirmModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ $title }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>{{ $confirm_message }}</p>
            </div>
            <div class="modal-footer">
                <form id="form_delete" action="#" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button class="btn btn-danger">{{ __('message.yes')}} </button>
                    <button type="button" class="btn btn-secondary btn-cancel-delete" 
                        data-dismiss="modal">{{ __('message.no') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
