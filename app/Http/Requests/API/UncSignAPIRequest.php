<?php

namespace App\Http\Requests\API;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Config;

class UncSignAPIRequest extends FormRequest
{

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
            'url_file_sign' => 'required',
            'name' => 'required'
        ];

        return $rules;
    }
    protected function failedValidation(Validator $validator)
    {
        if (Request()->route()->getPrefix() == 'api') {
            $errors = (new ValidationException($validator))->errors();
            throw new HttpResponseException(response()->json(['status' => 'errors', 'message' => $errors,
            ], 400));
        }
    }
}
