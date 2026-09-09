<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemPhoto extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'item_id',
        'photo_url',
        'created_at',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
