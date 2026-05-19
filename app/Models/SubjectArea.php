<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubjectArea extends Model {
    protected $primaryKey = 'area_id';
    protected $fillable = ['area_name', 'description', 'parent_area_id'];
    protected $casts = ['area_id' => 'integer', 'parent_area_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
    public function datasets(): HasMany { return $this->hasMany(Dataset::class, 'subject_area_id', 'area_id'); }
    public function children(): HasMany { return $this->hasMany(SubjectArea::class, 'parent_area_id', 'area_id'); }
    public function parent(): BelongsTo { return $this->belongsTo(SubjectArea::class, 'parent_area_id', 'area_id'); }
}