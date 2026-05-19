<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Keyword extends Model
{
    protected $primaryKey = 'keyword_id'; // ✅ FIX: ganti dari [nama_id]
    public $incrementing = true;
    protected $keyType = 'int';
    
    protected $fillable = ['keyword_name', 'description'];
    
    public function datasets(): BelongsToMany
    {
        return $this->belongsToMany(
            Dataset::class,
            'dataset_keyword',
            'keyword_id',  // ✅ FK di pivot untuk Keyword
            'dataset_id'   // ✅ FK di pivot untuk Dataset
        )->withTimestamps();
    }
}