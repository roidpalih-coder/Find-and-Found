<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'instagram_handle',
        'domicile_city',
        'avatar_url',
        'reputation_points',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'reputation_points' => 'integer',
        ];
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function claims()
    {
        return $this->hasMany(Claim::class, 'claimant_id');
    }

    public function notifications()
    {
        return $this->hasMany(AppNotification::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
