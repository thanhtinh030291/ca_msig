<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\RoomAndBoard;

class CreateRoomAndBoardRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        
        return [
            'name' => 'bail|min:8|max:500|required',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return RoomAndBoard::$rules;
    }
}
