<div class="row condition_advance">
    <div class="col-md-5">
        @if (isset($sort_list) && isset($sort_by))
        {{ Form::label('order_by', __('message.sort_option'), ['class' => 'labelas']) }}
        {{ Form::select('sort_by', $sort_list, $sort_by, [
            'class' => 'form-control sort_by'
        ])}}
        @endif
    </div>
    <div class="col-md-2"></div>
    <div class="col-md-5 limit_paginate_info">
        @if (isset($list))
        {{ __('message.display_result') }}{{ 
            Form::select('limit', $limit_list, $limit, [
                'class' => 'form-control limit_get'
            ])
        }}{{  __('message.unit_symbol')}} {{
            __('message.showing_range', [
                'begin' => empty($list) ? 0 : $list->firstItem(),
                'end'   => empty($list) ? 0 : $list->lastItem(),
            ]) 
        }} / {{ 
            __('message.of_total'). empty($list) ? 0 : $list->total() . __('message.unit_symbol')
        }}
        @endif
    </div>
</div>
