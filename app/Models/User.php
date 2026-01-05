<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    // User type constants
    const TYPE_CUSTOMER = 'customer';
    const TYPE_PROVIDER = 'provider';
    const TYPE_ADMIN = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Provider profile (if user is a service provider)
     */
    public function provider(): HasOne
    {
        return $this->hasOne(Provider::class);
    }

    /**
     * Filament panel access control
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Admin panel - only admins
        if ($panel->getId() === 'admin') {
            return $this->user_type === self::TYPE_ADMIN || $this->hasRole('super_admin');
        }

        // Provider panel - only providers
        if ($panel->getId() === 'provider') {
            return $this->user_type === self::TYPE_PROVIDER && $this->provider?->isApproved();
        }

        return true;
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    /**
     * Check if user is a provider
     */
    public function isProvider(): bool
    {
        return $this->user_type === self::TYPE_PROVIDER;
    }

    /**
     * Check if user is a customer
     */
    public function isCustomer(): bool
    {
        return $this->user_type === self::TYPE_CUSTOMER;
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->user_type === self::TYPE_ADMIN || $this->isSuperAdmin();
    }
}

