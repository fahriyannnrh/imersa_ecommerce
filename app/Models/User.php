<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
        'google_id',
        'google_avatar',
        'google_token',
        'google_refresh_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google_token',
        'google_refresh_token',
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
     * Check if user has linked their Google account.
     */
    public function isGoogleLinked(): bool
    {
        return !empty($this->google_id);
    }

    /**
     * Check if user has a set password.
     */
    public function hasPassword(): bool
    {
        return !empty($this->password);
    }

    /**
     * Get user avatar (Google avatar or default initials placeholder).
     */
    public function getAvatarUrl(): string
    {
        if (!empty($this->google_avatar)) {
            return $this->google_avatar;
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=4338ca&color=ffffff&bold=true';
    }

    /**
     * Check if user has administrator role.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->email === 'admin@imersa.test';
    }

    /**
     * Check if user is a customer/regular user.
     */
    public function isCustomer(): bool
    {
        return !$this->isAdmin();
    }
}
