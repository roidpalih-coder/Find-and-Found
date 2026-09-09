<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'type'           => ['required', 'in:lost,found'],
            'title'          => ['required', 'string', 'max:150'],
            'category_id'    => ['required', 'integer', 'exists:categories,id'],
            'description'    => ['required', 'string'],
            'secret_details' => ['nullable', 'string'],
            'incident_date'  => ['required', 'date'],
            'location_name'  => ['required', 'string', 'max:150'],
            'district'       => ['required', 'string', 'max:60'],
            'latitude'       => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'      => ['nullable', 'numeric', 'between:-180,180'],
            'reward_offered' => ['nullable', 'string', 'max:100'],
            'photo'          => ['nullable', 'image', 'max:5120'],
            'photos'         => ['nullable', 'array', 'max:4'],
            'photos.*'       => ['image', 'max:5120'],
        ];
    }
}
