<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserContactsUpdateRequest extends FormRequest
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
            'vk' => ['max:80', 'nullable'],
            'telegram' => ['max:80', 'nullable'],
            'instagram' => ['max:80', 'nullable']
        ];
    }
}
