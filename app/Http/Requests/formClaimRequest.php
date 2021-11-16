<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\validSelectField;
use App\Http\Traits\SanitizesInput;

class formClaimRequest extends FormRequest
{
    use SanitizesInput;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            '_column.*' => 'distinct|nullable',
            '_column' => [ new validSelectField()],
            'file'          => 'required_with:_row',
            'code_claim'      => 'required|numeric|unique:claim,code_claim,NULL,id,deleted_at,NULL',
            'barcode' => 'required',
        ];
        if ($this->method() != 'POST') {
            $rules['code_claim'] = 'required|numeric';
        }
        return $rules;
    }
}
