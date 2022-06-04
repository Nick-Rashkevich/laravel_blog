<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class AuthLoginRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => [
                'required',
                'regex:/\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,6}/',
            ],
            'password' => [
                Password::min(4)->letters()->mixedCase()->numbers()
            ]
        ];
    }
}
