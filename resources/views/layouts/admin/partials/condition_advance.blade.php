@php
    $urlPost = isset($urlPost) ? $urlPost : null;
    $search_params = isset($search_params) ? json_encode($search_params) : null;
@endphp
<div class="row condition_advance">
    <div class="col-md-7"></div>
    <div class="col-md-5">
        {{ __('message.display_result') }}{{ 
            Form::select('limit', $limit_list, $limit, [
                'class' => 'form-control limit_get lengthChange', 'data-url' => $urlPost, 'data-article' => $search_params
            ])
        }}{{  __('message.unit_symbol')}} {{
            __('message.showing_range', [
                'begin' => empty($list) ? 0 : $list->firstItem(),
                'end'   => empty($list) ? 0 : $list->lastItem(),
            ]) 
        }} / {{ 
            __('message.of_total'). empty($list) ? 0 : $list->total() . __('message.unit_symbol')
        }}
    </div>
</div>
