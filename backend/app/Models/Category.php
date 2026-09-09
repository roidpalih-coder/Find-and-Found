<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'is_priority_document',
    ];

    protected function casts(): array
    {
        return [
            'is_priority_document' => 'boolean',
        ];
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
