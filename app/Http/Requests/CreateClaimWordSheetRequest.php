<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\ClaimWordSheet;

class CreateClaimWordSheetRequest extends FormRequest
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
            'mem_ref_no'    => 'required',
            'claim_id'      => 'required|unique:claim_word_sheet',
        ];
        return $rules;
    }
    public function messages() 
    {
        return [
            'mem_ref_no.required' => 'Claim Chưa có mem_ref_no vui lòng kiểm Tra lại HBS',
            'claim_id.unique' => 'Work Sheet của Claim này đã tồn tại'
        ];
    }
}
