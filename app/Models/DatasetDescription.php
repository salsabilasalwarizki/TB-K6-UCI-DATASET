<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DatasetDescription extends Model
{
    // ✅ Tentukan nama tabel (karena tidak mengikuti konvensi plural)
    protected $table = 'dataset_descriptions';
    
    // ✅ Primary key
    protected $primaryKey = 'description_id';
    
    // ✅ Auto-increment
    public $incrementing = true;
    
    // ✅ Fillable fields (yang boleh di-mass assign)
    protected $fillable = [
        'dataset_id',
        'purpose',
        'funding',
        'instances_represent',
        'data_splits',
        'sensitive_data',
        'preprocessing',
        'additional_info',
        'citation_requests',
    ];
    
    // ✅ Casts (untuk konversi otomatis)
    protected $casts = [
        'description_id' => 'integer',
        'dataset_id' => 'integer',
    ];
    
    // ✅ Relasi ke Dataset
    public function dataset(): BelongsTo
    {
        return $this->belongsTo(Dataset::class, 'dataset_id', 'dataset_id');
    }
}  // ← Hanya SATU tanda } di akhir file