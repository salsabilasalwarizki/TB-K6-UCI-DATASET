<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Paper extends Model
{
    protected $primaryKey = 'paper_id';
    protected $keyType = 'bigint';
    public $incrementing = true;
    
    protected $fillable = [
        'title', 'doi', 'authors', 'venue', 'publication_year',
        'volume', 'issue', 'pages', 'url', 'pdf_url', 'abstract', 'bibtex'
    ];
    
    protected $casts = [
        'paper_id' => 'integer',
        'publication_year' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    public function datasets(): BelongsToMany
    {
        return $this->belongsToMany(
            Dataset::class, 'dataset_papers', 'paper_id', 'dataset_id'
        )->withPivot('citation_type', 'is_primary', 'created_at');
    }
}