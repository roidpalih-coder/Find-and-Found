<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'type',
        'title',
        'description',
        'secret_details',
        'incident_date',
        'location_name',
        'district',
        'latitude',
        'longitude',
        'primary_photo_url',
        'reward_offered',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'incident_date' => 'datetime',
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function photos()
    {
        return $this->hasMany(ItemPhoto::class);
    }

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    public function activeClaim()
    {
        return $this->hasOne(Claim::class)->whereIn('status', ['pending', 'approved']);
    }
}
