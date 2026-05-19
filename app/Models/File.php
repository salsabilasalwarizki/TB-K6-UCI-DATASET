<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    protected $primaryKey = 'file_id';
    protected $keyType = 'bigint';
    public $incrementing = true;
    
    protected $fillable = [
        'filename', 'original_filename', 'file_path', 'file_size_bytes',
        'mime_type', 'file_format', 'description', 'checksum_md5', 'checksum_sha256'
    ];
    
    protected $casts = [
        'file_id' => 'integer',
        'file_size_bytes' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    public function datasets(): BelongsToMany
    {
        return $this->belongsToMany(
            Dataset::class, 'dataset_files', 'file_id', 'dataset_id'
        )->withPivot('file_role', 'is_default', 'display_order');
    }
    
    public function getDownloadUrl(): string
    {
        return Storage::url($this->file_path);
    }
    
    public function getFormattedSize(): string
    {
        $bytes = $this->file_size_bytes;
        if ($bytes >= 1073741824) return round($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024) return round($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }
}