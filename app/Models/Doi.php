<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doi extends Model
{
    protected $primaryKey = 'doi_id'; // ✅ FIX
    public $incrementing = true;
    protected $keyType = 'int';
    
    protected $fillable = ['doi_string', 'resolution_url'];
    
    public function datasets(): HasMany
    {
        return $this->hasMany(Dataset::class, 'doi_id', 'doi_id');
    }
}