<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'id';
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'email_verified_at',
        'last_login_at',
        'banned_at',
        'remember_token',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'banned_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];
    
    // Relationships
    public function datasets(): HasMany
    {
        return $this->hasMany(Dataset::class, 'user_id');
    }
    
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'user_id');
    }
    
    public function downloads(): HasMany
    {
        return $this->hasMany(Download::class, 'user_id');
    }
    
    // Helper methods
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'superadmin']);
    }
    
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }
    
    public function isBanned(): bool
    {
        return $this->banned_at !== null;
    }
    
    public function isActive(): bool
    {
        return $this->is_active && !$this->isBanned();
    }
}