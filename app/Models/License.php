<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class License extends Model
{
    protected $primaryKey = 'license_id'; // ✅ FIX
    public $incrementing = true;
    protected $keyType = 'int';
    
    protected $fillable = ['license_name', 'description', 'license_url'];
    
    public function datasets(): HasMany
    {
        return $this->hasMany(Dataset::class, 'license_id', 'license_id');
    }
}