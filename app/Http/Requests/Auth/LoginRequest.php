<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['boolean'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if (! auth()->attempt($this->safe(['email', 'password']), $this->boolean('remember'))) {
                    $validator->errors()->add('password', 'These credentials do not match our records.');
                }
            },
        ];
    }
}
