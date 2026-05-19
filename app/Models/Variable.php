<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Variable extends Model
{
    protected $primaryKey = 'variable_id'; // ✅ FIX
    public $incrementing = true;
    protected $keyType = 'int';
    
    protected $fillable = [
        'dataset_id', 'variable_name', 'role', 'type',
        'description', 'units', 'has_missing', 'order_index'
    ];
    
    public function dataset(): BelongsTo
    {
        return $this->belongsTo(Dataset::class, 'dataset_id', 'dataset_id');
    }
}