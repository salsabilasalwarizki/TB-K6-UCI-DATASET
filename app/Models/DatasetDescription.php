<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DatasetDescription extends Model
{
    protected $primaryKey = 'description_id';
    protected $keyType = 'bigint';
    public $incrementing = true;
    
    protected $table = 'dataset_descriptions';
    
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
    
    protected $casts = [
        'description_id' => 'integer',
        'dataset_id' => 'integer',
        // ✅ PENTING: Jangan cast ke array/object jika kolom adalah TEXT/VARCHAR
        // Hapus baris berikut jika ada:
        // 'purpose' => 'array',
        // 'instances_represent' => 'object',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    /**
     * Relationship ke Dataset
     */
    public function dataset(): BelongsTo
    {
        return $this->belongsTo(Dataset::class, 'dataset_id', 'dataset_id');
    }
    
    /**
     * Accessor: Pastikan return string, bukan object
     */
    public function getPurposeAttribute($value): ?string
    {
        if ($value === null) return null;
        if (is_object($value)) return json_encode($value);
        if (is_array($value)) return json_encode($value);
        return (string) $value;
    }
    
    public function getInstancesRepresentAttribute($value): ?string
    {
        if ($value === null) return null;
        if (is_object($value)) return json_encode($value);
        if (is_array($value)) return json_encode($value);
        return (string) $value;
    }
    
    public function getFundingAttribute($value): ?string
    {
        if ($value === null) return null;
        if (is_object($value)) return json_encode($value);
        if (is_array($value)) return json_encode($value);
        return (string) $value;
    }
    
    public function getAdditionalInfoAttribute($value): ?string
    {
        if ($value === null) return null;
        if (is_object($value)) return json_encode($value);
        if (is_array($value)) return json_encode($value);
        return (string) $value;
    }
    
    public function getDataSplitsAttribute($value): ?string
    {
        if ($value === null) return null;
        if (is_object($value)) return json_encode($value);
        if (is_array($value)) return json_encode($value);
        return (string) $value;
    }
    
    public function getSensitiveDataAttribute($value): ?string
    {
        if ($value === null) return null;
        if (is_object($value)) return json_encode($value);
        if (is_array($value)) return json_encode($value);
        return (string) $value;
    }
    
    public function getPreprocessingAttribute($value): ?string
    {
        if ($value === null) return null;
        if (is_object($value)) return json_encode($value);
        if (is_array($value)) return json_encode($value);
        return (string) $value;
    }
    
    public function getCitationRequestsAttribute($value): ?string
    {
        if ($value === null) return null;
        if (is_object($value)) return json_encode($value);
        if (is_array($value)) return json_encode($value);
        return (string) $value;
    }
}