<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class AdminRequest extends FormRequest
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
        'name'          => 'required|max:100|min:2',
        'email'         => 'email|required|max:100|unique:users',
        'profile_image' => 'mimes:jpeg,jpg,png,jpe|nullable|max:1999',
        'password' => 'required|string|min:6',
        ];
        $userId = $this->route('admin');
        if  ($this->method() == 'PUT')
        {
            $rules['password'] = 'max:20|min:6';
            $rules['email'] = ['required', 'email', 'max:100', Rule::unique('users')->ignore($userId)];
        }
        return $rules;
    }
    
}
