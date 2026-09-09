<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClaimRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'proof_description' => ['required', 'string', 'min:20'],
            'proof_photo'       => ['nullable', 'image', 'max:5120'],
        ];
    }
}
