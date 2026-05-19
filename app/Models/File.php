<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class File extends Model
{
    protected $primaryKey = 'file_id'; // ✅ FIX
    public $incrementing = true;
    protected $keyType = 'int';
    
    protected $fillable = [
        'dataset_id', 'filename', 'original_filename', 'file_format',
        'file_size', 'file_size_bytes', 'mime_type', 'is_primary'
    ];
    
    public function datasets(): BelongsToMany
{
    return $this->belongsToMany(
        Dataset::class,
        'dataset_files',
        'file_id',
        'dataset_id'
    )
    ->withPivot('file_role', 'is_default', 'display_order');
}
}