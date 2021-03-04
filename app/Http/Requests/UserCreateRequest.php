<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6', 'max:20'],
            'name' => ['nullable', 'min:2', 'max:20'],
            'job' => ['nullable', 'min:5', 'max:60'],
            'phone' => ['nullable', 'min:8', 'max:16'],
            'address' => ['nullable', 'max:60'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,png', 'max:3000'],
            'vk' => ['nullable', 'max:50'],
            'telegram' => ['nullable', 'max:50'],
            'instagram' => ['nullable', 'max:50']
        ];
    }
}
