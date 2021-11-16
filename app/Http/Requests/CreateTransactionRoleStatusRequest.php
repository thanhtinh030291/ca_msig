<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\TransactionRoleStatus;
use App\Rules\validTranstionData;
use Illuminate\Support\Facades\Request;

class CreateTransactionRoleStatusRequest extends FormRequest
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
        $current_status = Request::get('current_status');
        $role = Request::get('role');
        $to_status = Request::get('to_status');
        $rules = [
            'level_id' => new validTranstionData($current_status, $role, $to_status)
        ];

        return $rules;
    }
}
