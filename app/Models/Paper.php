<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Paper extends Model
{
    protected $primaryKey = 'paper_id'; // ✅ FIX
    public $incrementing = true;
    protected $keyType = 'int';
    
    protected $fillable = ['title', 'authors', 'publication_year', 'venue', 'paper_doi', 'paper_url'];
    
    public function datasets(): BelongsToMany
    {
        return $this->belongsToMany(
            Dataset::class,
            'dataset_paper',
            'paper_id',
            'dataset_id'
        )->withTimestamps();
    }
}