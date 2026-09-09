<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'             => ['sometimes', 'string', 'max:100'],
            'phone_number'     => ['sometimes', 'string', 'max:20'],
            'instagram_handle' => ['nullable', 'string', 'max:50'],
            'domicile_city'    => ['nullable', 'string', 'max:50'],
            'avatar'           => ['nullable', 'image', 'max:2048'],
        ];
    }
}
