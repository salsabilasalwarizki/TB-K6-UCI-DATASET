<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Keyword extends Model
{
    protected $primaryKey = 'keyword_id';
    protected $keyType = 'bigint';
    public $incrementing = true;
    
    protected $fillable = ['keyword_name', 'slug', 'category', 'description'];
    
    protected $casts = [
        'keyword_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
  /**
 * Get all datasets that use this keyword
 */
public function datasets(): BelongsToMany
{
    return $this->belongsToMany(
        Dataset::class, 
        'dataset_keywords', 
        'keyword_id', 
        'dataset_id'
    )->withTimestamps();
}
    
    public function getDatasetsCountAttribute(): int
    {
        return $this->datasets()->count();
    }
}