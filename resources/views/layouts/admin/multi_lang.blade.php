@if(!empty($name_submit) && !empty($label_name) && !empty($lang_list) && isset($data))
@php 
    $required = isset($required) ? $required : true
@endphp
@foreach ($lang_list as $key => $value)
    @php
        $name_key  = $name_submit . '_' . $key;
        $label_key = $label_name . $value;
        $class_key = isset($class) ? $class : 'form-control';
    @endphp
    @if ($key == 'en')
        <div class="row multi_lang parent {{ $name_submit }}">
            <div class="col-sm-2 col-form-label d-flex justify-content-end">
                {{ Form::label($name_key, $label_key, array('class' => 'labelas')) }}
                @if($required)
                <span class="text-danger">*</span>
                @endif
            </div>
            @if(isset($type) && $type == 'textarea')
                <div class="col-sm-9 form-group">
                    {{ Form::textarea($name_key, old($name_key, data_get($data, $name_key)), ['class' => $class])}}
                </div>
            @elseif(isset($type) && $type == 'show')
                <div class="col-sm-9 form-group">
                    <input class="{{ $class_key }}" value="{{old($name_key, data_get($data, $name_key))}}">
                </div>
            @else
                <div class="col-sm-9">
                    {{ Form::text($name_key, old($name_key, data_get($data, $name_key)), [
                        'class'       => $class_key ,
                        'placeholder' => $placeholder ?? '',
                        'required'    => $required,
                        'minlength'   => $floor ?? '',
                        'maxlength'   => $ceil ?? '',
                    ])}}
                </div>
            @endif
            <div class="col-sm-1">
                <span class="show_multi_lang arrow-down"></span>
            </div>
        </div>
    @else
        <div class="multi_lang {{ $name_key }} off row">
            <div class="col-sm-2 col-form-label d-flex justify-content-end">
                {{ Form::label($name_key, $label_key, array('class' => 'labelas')) }}
            </div>
            @if(isset($type) && $type == 'textarea')
                <div class="col-sm-9 pl-5 form-group ">
                    {{ Form::textarea($name_key, old($name_key, data_get($data, $name_key)), ['class' => $class])}}
                </div>
            @elseif(isset($type) && $type == 'show')
                <div class="col-sm-9 pl-5 form-group ">
                    <input class="{{ $class_key }}" value="{{old($name_key, data_get($data, $name_key))}}">
                </div>
            @else
                <div class="col-sm-9 pl-5">
                    {{ Form::text($name_key, old($name_key, data_get($data, $name_key)), [
                        'class'       => $class_key ,
                        'placeholder' => $placeholder ?? '',
                        'minlength'   => $floor ?? '',
                        'maxlength'   => $ceil ?? '',
                    ])}}
                </div>
            @endif
        </div>
    @endif
@endforeach
@endif