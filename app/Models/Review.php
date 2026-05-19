<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $primaryKey = 'review_id';
    protected $keyType = 'bigint';
    public $incrementing = true;
    
    protected $fillable = [
        'dataset_id', 'user_id', 'rating', 'title', 'content',
        'pros', 'cons', 'is_verified', 'helpful_count'
    ];
    
    protected $casts = [
        'review_id' => 'integer',
        'dataset_id' => 'integer',
        'user_id' => 'integer',
        'rating' => 'decimal:2',
        'is_verified' => 'boolean',
        'helpful_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    public function dataset(): BelongsTo
    {
        return $this->belongsTo(Dataset::class, 'dataset_id', 'dataset_id');
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}