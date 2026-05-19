<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class License extends Model {
    protected $primaryKey = 'license_id';
    protected $fillable = ['license_name', 'description', 'license_url'];
    protected $casts = ['license_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
    public function datasets(): HasMany { return $this->hasMany(Dataset::class, 'license_id', 'license_id'); }
}