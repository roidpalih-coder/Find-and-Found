<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'             => ['required', 'string', 'max:100'],
            'email'            => ['required', 'email', 'max:100', 'unique:users,email'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
            'phone_number'     => ['required', 'string', 'max:20'],
            'instagram_handle' => ['nullable', 'string', 'max:50'],
            'domicile_city'    => ['nullable', 'string', 'max:50'],
        ];
    }
}
