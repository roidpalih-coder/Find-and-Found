<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $fillable = [
        'item_id',
        'claimant_id',
        'proof_description',
        'proof_photo_url',
        'status',
        'response_notes',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function claimant()
    {
        return $this->belongsTo(User::class, 'claimant_id');
    }
}
