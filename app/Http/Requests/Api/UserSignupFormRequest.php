<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rules\Password;
use App\Http\Requests\BaseAPIValidator;

class UserSignupFormRequest extends BaseAPIValidator
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised(),
            ],
            'password_confirmation' => 'required|min:8',
        ];
    }
}
