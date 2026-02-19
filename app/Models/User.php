<?php

namespace App\Models;

use App\Enums\Role;
use Database\Factories\UserFactory;
use Laravel\Sanctum\HasApiTokens;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'gender',
        'subscription_id',
        'oauth_provider',
        'oauth_provider_id',
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
            'role' => Role::class,
        ];
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === Role::Admin;
    }

    public function isMaster(): bool
    {
        return $this->role === Role::Master;
    }

    public function displayTitle(): string
    {
        if ($this->role !== Role::Master) {
            return ucfirst($this->role->value);
        }

        return $this->gender === 'female' ? 'Майстриня' : 'Майстер';
    }
}
