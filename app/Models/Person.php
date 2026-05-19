<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany};

class Person extends Model
{
    protected $primaryKey = 'person_id';
    protected $keyType = 'bigint';
    public $incrementing = true;
    
    protected $fillable = [
        'user_id', 'name', 'email', 'affiliation', 'orcid', 'profile_url'
    ];
    
    protected $casts = [
        'person_id' => 'integer',
        'user_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function datasets(): BelongsToMany
    {
        return $this->belongsToMany(
            Dataset::class, 'dataset_contributors', 'person_id', 'dataset_id'
        )->withPivot('contribution_role', 'display_order', 'created_at');
    }
    
    public function papers(): BelongsToMany
    {
        return $this->belongsToMany(
            Paper::class, 'paper_authors', 'person_id', 'paper_id'
        )->withPivot('author_order', 'is_corresponding');
    }
}