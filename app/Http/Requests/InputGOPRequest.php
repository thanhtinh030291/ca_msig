<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Traits\SanitizesInput;

class InputGOPRequest extends FormRequest
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
            'prov_gop_pres_amt' => 'required|min:0|integer'
        ];
        return $rules;
    }

    public function sanitize(array $input)
    {
        $price_list = removeFormatPriceList(
            [
                'prov_gop_pres_amt' => data_get($input, 'prov_gop_pres_amt'),
            ]
        );
        return $price_list;
    }
}
