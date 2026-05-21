<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'email_verified_at',
        'google_id',
        'github_id',
        'avatar',
        'last_login_at',
        'banned_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'banned_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Get the datasets owned by the user.
     */
    public function datasets(): HasMany
    {
        return $this->hasMany(Dataset::class, 'user_id');
    }

    /**
     * Get the reviews written by the user.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    /**
     * Get the downloads made by the user.
     */
    public function downloads(): HasMany
    {
        return $this->hasMany(Download::class, 'user_id');
    }

    // ===== HELPER METHODS =====

    /**
     * Check if user is admin or superadmin.
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'superadmin'], true);
    }

    /**
     * Check if user is superadmin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    /**
     * Check if user is banned.
     */
    public function isBanned(): bool
    {
        return $this->banned_at !== null;
    }

    /**
     * Check if user is active (not banned and is_active = true).
     */
    public function isActive(): bool
    {
        return $this->is_active && !$this->isBanned();
    }

    /**
     * Check if user has connected Google account.
     */
    public function hasGoogle(): bool
    {
        return !empty($this->google_id);
    }

    /**
     * Check if user has connected GitHub account.
     */
    public function hasGithub(): bool
    {
        return !empty($this->github_id);
    }

    /**
     * Check if user has password set (for social login users).
     */
    public function hasPassword(): bool
    {
        return !empty($this->password);
    }

    /**
     * Get user avatar URL with fallback.
     */
    public function getAvatarUrlAttribute(): string
    {
        if (!empty($this->avatar)) {
            return $this->avatar;
        }
        
        // Fallback to Gravatar
        return 'https://gravatar.com/avatar/' . md5(strtolower(trim($this->email))) . '?d=mp&s=200';
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->whereNull('banned_at');
    }

    /**
     * Scope a query to only include admin users.
     */
    public function scopeAdmins($query)
    {
        return $query->whereIn('role', ['admin', 'superadmin']);
    }

    /**
     * Scope a query to only include users with social login.
     */
    public function scopeWithSocialLogin($query, ?string $provider = null)
    {
        if ($provider === 'google') {
            return $query->whereNotNull('google_id');
        }
        
        if ($provider === 'github') {
            return $query->whereNotNull('github_id');
        }
        
        return $query->whereNotNull('google_id')
                    ->orWhereNotNull('github_id');
    }
}