<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RewardRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'badge'   => ['nullable', 'string', 'in:honest_finder,quick_responder,community_hero'],
            'message' => ['nullable', 'string', 'max:500'],
        ];
    }
}
