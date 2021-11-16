@extends('templateEmail.main')
@section('content')
    <table style="width:100%;border-collapse:collapse">
        <tbody>
            <tr> 
                <td style="vertical-align:top;font-size:13px;line-height:18px;font-family:Arial,sans-serif">
                    <h3 style="color:black;font-size:18px;margin:15px 0 0 0;font-weight:normal">{{$user->company_name}}</h3> 
                    <p style="margin:0 0 8px 0;font:12px/16px Arial,sans-serif"> {{__('web.message_1')}}</p>
                </td> 
            </tr>
            <tr>
                <td style="border-bottom:1px solid rgb(204,204,204);vertical-align:top;font-size:13px;line-height:18px;font-family:Arial,sans-serif">
                    <h3 style="color:black;font-size:18px;margin:15px 0 0 0;font-weight:normal">{{__('web.order_contents')}}</h3>
                </td>
            </tr>
            <tr>
                <td
                    style="padding-left:32px;vertical-align:top;font-size:13px;line-height:18px;font-family:Arial,sans-serif">
                    <table id="m_4772503480705120267orderDetails"
                        style="width:100%;border-collapse:collapse">
                        <tbody>
                            <tr>
                                <td style="vertical-align:top;font-size:13px;line-height:18px;font-family:Arial,sans-serif"> {{__('web.order_code')}}: 
                                    <span style="text-decoration:none;color:rgb(0,102,153);font:12px/16px Arial,sans-serif">
                                        {{isset($data['order_code']) ? $data['order_code'] : "none"}}
                                    </span>
                                    <br>
                                    <span style="font-size:12px;color:rgb(102,102,102)">{{__('web.order_date')}}：
                                        {{isset($data['order_date']) ? $data['order_date'] : "none"}}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            @if ($data['paid'])
                @php
                    $totalAmount = 0 ;
                @endphp
                <tr>
                    <td style="padding-left:30px;vertical-align:top;font-size:13px;line-height:18px;font-family:Arial,sans-serif">
                        <h3 style="color:black;font-size:16px;margin:15px 0 0 0;font-weight:normal">{{__('web.order_paid')}}</h3>
                    </td>
                </tr>
                @foreach ($data['paid'] as $item)
                    @php
                        $totalAmount += $item['quantity_adult'] * $item['price_adult'] +  $item['quantity_child'] * $item['price_child'];
                        $dir = "";
                        $type = "";
                        $name = "";
                        $image = public_path(config('constants.image_default'));
                        switch ($item['item_type']) {
                            case config('constants.typeService.ticket'):
                                $dir = config('constants.ticketStorage');
                                $type = __('web.ticket');
                                $name = $item['ticket']['name'];
                                $image =  empty($item['ticket']['images']) ?  $image  : public_path($dir . $item['ticket']['images'][0]['uri']) ;
                                break;
                            case config('constants.typeService.trans'):
                                $dir = config('constants.transStorage');
                                $type = __('web.trans');
                                $name = $item['trans']['name'];
                                $image =  empty($item['trans']['images']) ?  $image  : public_path($dir . $item['trans']['images'][0]['uri']) ;
                                break;
                            default:
                                $dir = config('constants.tourStorage');
                                $type = __('web.tour');
                                $name = $item['tour']['name'];
                                $image =  empty($item['tour']['images']) ?  $image  : public_path($dir . $item['tour']['images'][0]['uri']) ;
                                break;
                        }
                    @endphp
                <tr>
                    <td style="padding-left:32px;vertical-align:top;font-size:13px;line-height:18px;font-family:Arial,sans-serif">
                        <table  style="border-top:3px solid rgb(45,55,65);width:95%;border-collapse:collapse">
                            <tbody>
                                <tr>
                                    <td style="font-size:14px;padding:11px 18px 18px 18px;background-color:rgb(239,239,239);width:50%;vertical-align:top;line-height:18px;font-family:Arial,sans-serif">
                                        <p style="margin:2px 0 9px 0;font:14px Arial,sans-serif"> 
                                            <span style="font-size:14px;color:rgb(102,102,102)">{{__('web.date_of_service')}}：</span> <br>
                                            <b> {{isset($item['date_of_service']) ? $item['date_of_service'] : "none"}} </b> 
                                        </p>
                                        <p style="margin:2px 0 9px 0;font:14px Arial,sans-serif"> 
                                            <span style="font-size:14px;color:rgb(102,102,102)">{{__('web.order_item_code')}}：</span> <br>
                                            <b> {{isset($item['order_item_code']) ? $item['order_item_code'] : "none"}} </b> 
                                        </p>
                                        <p style="margin:2px 0 9px 0;font:14px Arial,sans-serif"> 
                                            <span style="font-size:14px;color:rgb(102,102,102)">{{__('web.item_code')}}：</span> <br>
                                            <b> {{isset($item['item_code']) ? $item['item_code'] : "none"}} </b> 
                                        </p>
                                    </td>
                                    <td style="font-size:14px;padding:11px 18px 18px 18px;background-color:rgb(239,239,239);width:50%;vertical-align:top;line-height:18px;font-family:Arial,sans-serif">
                                        <p style="margin:2px 0 9px 0;font:14px Arial,sans-serif"> 
                                            <span style="font-size:14px;color:rgb(102,102,102)">{{__('web.information')}}：</span> <br>
                                            <b> 
                                                {{__('web.quantity_adult')}}: {{isset($item['quantity_adult']) ? $item['quantity_adult'] : "none"}} <br>
                                                {{__('web.quantity_child')}}: {{isset($item['quantity_child']) ? $item['quantity_child'] : "none"}} <br>
                                                {{__('web.price_adult')}}: ￥ {{isset($item['price_adult']) ? $item['price_adult'] : "none"}} <br>
                                                {{__('web.price_child')}}: ￥ {{isset($item['price_child']) ? $item['price_child'] : "none"}} <br>
                                            </b> 
                                        </p>
                                    </td> 
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding-left:32px;vertical-align:top;font-size:13px;line-height:18px;font-family:Arial,sans-serif">
                                        <table style="width:95%;border-collapse:collapse" id="m_itemDetails">
                                            <tbody>
                                                <tr>
                                                    <td style="width:150px;text-align:center;padding:16px 0 10px 0;vertical-align:top;font-size:13px;line-height:18px;font-family:Arial,sans-serif">
                                                        <img id="m_asin" src="{{ $message->embed($image) }}" style="border:0 max-width:150px" > 
                                                    </td>
                                                    <td style="color:rgb(102,102,102);padding:10px 10px 10px 10px;vertical-align:top;font-size:13px;line-height:18px;font-family:Arial,sans-serif">
                                                        {{__('web.product_name')}}: {{$name}}<br>
                                                        {{__('web.product_type')}}: {{$type}}
                                                        <br>
                                                    </td>
                                                    <td style="width:80px;text-align:right;font-size:14px;padding:10px 10px 0 0;vertical-align:top;line-height:18px;font-family:Arial,sans-serif">
                                                        <strong>￥ {{ $item['quantity_adult'] * $item['price_adult'] +  $item['quantity_child'] * $item['price_child'] }}</strong>
                                                        <br>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td style="float: right;color: blue;font-size: medium;">
                        {{__('web.product_subtotal')}}: ¥ {{$totalAmount}}<br>
                        {{__('web.shipping_handling')}}: ¥ 0 <br>
                        {{__('web.total_order')}}: ¥ {{$totalAmount}} <br>
                        {{__('web.method_payment')}}: Credit	<br>
                    </td>
                </tr>
            @endif
            @if ($data['pending'])
                <tr>
                    <td style="padding-left:30px;vertical-align:top;font-size:13px;line-height:18px;font-family:Arial,sans-serif">
                        <h3 style="color:black;font-size:16px;margin:15px 0 0 0;font-weight:normal">{{__('web.order_pending')}}</h3>
                    </td>
                </tr>
                @foreach ($data['pending'] as $item)
                <tr>
                    <td style="padding-left:32px;vertical-align:top;font-size:13px;line-height:18px;font-family:Arial,sans-serif">
                        <table  style="border-top:3px solid rgb(45,55,65);width:95%;border-collapse:collapse">
                            <tbody>
                                <tr>
                                    <td style="font-size:14px;padding:11px 18px 18px 18px;background-color:rgb(239,239,239);width:50%;vertical-align:top;line-height:18px;font-family:Arial,sans-serif">
                                        <p style="margin:2px 0 9px 0;font:14px Arial,sans-serif"> 
                                            <span style="font-size:14px;color:rgb(102,102,102)">{{__('web.date_of_service')}}：</span> <br>
                                            <b> {{isset($item['date_of_service']) ? $item['date_of_service'] : "none"}} </b> 
                                        </p>
                                        <p style="margin:2px 0 9px 0;font:14px Arial,sans-serif"> 
                                            <span style="font-size:14px;color:rgb(102,102,102)">{{__('web.order_item_code')}}：</span> <br>
                                            <b> {{isset($item['order_item_code']) ? $item['order_item_code'] : "none"}} </b> 
                                        </p>
                                        <p style="margin:2px 0 9px 0;font:14px Arial,sans-serif"> 
                                            <span style="font-size:14px;color:rgb(102,102,102)">{{__('web.item_code')}}：</span> <br>
                                            <b> {{isset($item['item_code']) ? $item['item_code'] : "none"}} </b> 
                                        </p>
                                    </td>
                                    <td style="font-size:14px;padding:11px 18px 18px 18px;background-color:rgb(239,239,239);width:50%;vertical-align:top;line-height:18px;font-family:Arial,sans-serif">
                                        <p style="margin:2px 0 9px 0;font:14px Arial,sans-serif"> 
                                            <span style="font-size:14px;color:rgb(102,102,102)">{{__('web.information')}}：</span> <br>
                                            <b> 
                                                {{__('web.quantity_adult')}}: {{isset($item['quantity_adult']) ? $item['quantity_adult'] : "none"}} <br>
                                                {{__('web.quantity_child')}}: {{isset($item['quantity_child']) ? $item['quantity_child'] : "none"}} <br>
                                                {{__('web.price_adult')}}: ￥ {{isset($item['price_adult']) ? $item['price_adult'] : "none"}} <br>
                                                {{__('web.price_child')}}: ￥ {{isset($item['price_child']) ? $item['price_child'] : "none"}} <br>
                                            </b> 
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding-left:32px;vertical-align:top;font-size:13px;line-height:18px;font-family:Arial,sans-serif">
                                        <table style="width:95%;border-collapse:collapse" id="m_itemDetails">
                                            <tbody>
                                                <tr>
                                                    <td style="width:150px;text-align:center;padding:16px 0 10px 0;vertical-align:top;font-size:13px;line-height:18px;font-family:Arial,sans-serif">
                                                        @php
                                                            $dir = "";
                                                            $type = "";
                                                            $name = "";
                                                            $image = public_path(config('constants.image_default'));
                                                            switch ($item['item_type']) {
                                                                case config('constants.typeService.ticket'):
                                                                    $dir = config('constants.ticketStorage');
                                                                    $type = __('web.ticket');
                                                                    $name = $item['ticket']['name'];
                                                                    $image =  empty($item['ticket']['images']) ?  $image : public_path($dir . $item['ticket']['images'][0]['uri']) ;
                                                                    break;
                                                                default: 
                                                                    $dir = config('constants.transStorage');
                                                                    $type = __('web.trans');
                                                                    $name = $item['trans']['name'];
                                                                    $image =  empty($item['trans']['images']) ?  $image : public_path($dir . $item['trans']['images'][0]['uri']) ;
                                                                    break;
                                                            }
                                                        @endphp
                                                        <img id="m_asin" src="{{ $message->embed($image) }}" style="border:0 max-width:150px" > 
                                                    </td>
                                                    <td style="color:rgb(102,102,102);padding:10px 10px 10px 10px;vertical-align:top;font-size:13px;line-height:18px;font-family:Arial,sans-serif">
                                                        {{__('web.product_name')}}: {{$name}}<br>
                                                        {{__('web.product_type')}}: {{$type}}
                                                        <br>
                                                    </td>
                                                    <td style="width:80px;text-align:right;font-size:14px;padding:10px 10px 0 0;vertical-align:top;line-height:18px;font-family:Arial,sans-serif">
                                                    <strong>￥ {{ $item['quantity_adult'] * $item['price_adult'] +  $item['quantity_child'] * $item['price_child'] }}</strong>
                                                        <br>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>
@endsection