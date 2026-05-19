<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Variable extends Model
{
    protected $primaryKey = 'variable_id';
    protected $keyType = 'bigint';
    public $incrementing = true;
    
    protected $fillable = [
        'dataset_id', 'variable_name', 'display_name', 'role', 'variable_type',
        'description', 'unit', 'min_value', 'max_value', 'missing_count',
        'unique_count', 'display_order', 'is_visible'
    ];
    
    protected $casts = [
        'variable_id' => 'integer',
        'dataset_id' => 'integer',
        'min_value' => 'decimal:10',
        'max_value' => 'decimal:10',
        'missing_count' => 'integer',
        'unique_count' => 'integer',
        'is_visible' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    public function dataset(): BelongsTo
    {
        return $this->belongsTo(Dataset::class, 'dataset_id', 'dataset_id');
    }
    
    public function categories(): HasMany
    {
        return $this->hasMany(VariableCategory::class, 'variable_id', 'variable_id')
            ->orderBy('display_order');
    }
}