<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Image extends Model {
    protected $primaryKey = 'image_id';
    protected $fillable = ['filename', 'original_filename', 'file_path', 'file_size_bytes', 'mime_type', 'width', 'height', 'alt_text', 'caption', 'image_type'];
    protected $casts = ['image_id' => 'integer', 'width' => 'integer', 'height' => 'integer', 'file_size_bytes' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
    public function datasets(): BelongsToMany { return $this->belongsToMany(Dataset::class, 'dataset_images', 'image_id', 'dataset_id')->withPivot('role', 'display_order', 'is_primary'); }
    public function getUrl(): string { return Storage::url($this->file_path); }
}