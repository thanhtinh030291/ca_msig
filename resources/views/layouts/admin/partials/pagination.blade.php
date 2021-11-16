<div class="pagination_group">
@if (count($list) > 0)
    @if (!empty($append_link_data))
        {{ $list->appends($append_link_data)->links() }}
    @else
        {{ $list->links() }}
    @endif
    @if ((isset($not_show_detail) && $not_show_detail == false) || !isset($not_show_detail)) 
    {{ 
        __('message.showing') .' '. $list->firstItem() 
        .' '. __('message.to') .' '. $list->lastItem() 
        .' '. __('message.of_total') .' '. $list->total() 
    }}
    @endif
@endif
</div>
