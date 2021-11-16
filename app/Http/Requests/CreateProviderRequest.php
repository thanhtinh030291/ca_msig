<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Provider;
use App\Http\Traits\SanitizesInput;

class CreateProviderRequest extends FormRequest
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
            '_claim_no'    => 'required',
            '_amt'      => 'required|integer',
            '_comment'  => 'required',
            'PROV_CODE' => 'required',
        ];
        return $rules;
    }

    public function sanitize(array $input)
    {
        $price_list = removeFormatPriceList(
            [
                '_amt' => data_get($input, '_amt'),
            ]
        );
        return $price_list;
    }
}
