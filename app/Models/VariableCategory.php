<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VariableCategory extends Model
{
    protected $primaryKey = 'category_id';
    protected $keyType = 'bigint';
    public $incrementing = true;
    
    protected $fillable = [
        'variable_id', 'category_value', 'category_label', 'display_order'
    ];
    
    protected $casts = [
        'category_id' => 'integer',
        'variable_id' => 'integer',
        'display_order' => 'integer',
        'created_at' => 'datetime',
    ];
    
    public function variable(): BelongsTo
    {
        return $this->belongsTo(Variable::class, 'variable_id', 'variable_id');
    }
}