<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Download extends Model {
    protected $primaryKey = 'download_id';
    protected $fillable = ['dataset_id', 'file_id', 'user_id', 'ip_address', 'user_agent'];
    protected $casts = ['download_id' => 'integer', 'dataset_id' => 'integer', 'file_id' => 'integer', 'user_id' => 'integer', 'downloaded_at' => 'datetime'];
    public function dataset(): BelongsTo { return $this->belongsTo(Dataset::class, 'dataset_id', 'dataset_id'); }
    public function file(): BelongsTo { return $this->belongsTo(File::class, 'file_id', 'file_id'); }
    public function user(): BelongsTo { return $this->belongsTo(User::class, 'user_id'); }
}