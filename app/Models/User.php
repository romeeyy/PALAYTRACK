<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $attributes = [
        'theme_preference' => 'classic',
        'display_mode' => 'light',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'profile_photo_path',
        'theme_preference',
        'display_mode',
        'notifications_read_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'notifications_read_at' => 'datetime',
        ];
    }

    public function isOwner()
    {
        return $this->role === 'owner';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    public function isActive()
    {
        return $this->is_active;
    }
    public function deliveries()
{
    return $this->hasMany(Delivery::class, 'staff_id');
}
}
