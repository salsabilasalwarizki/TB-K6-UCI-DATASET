<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubjectArea extends Model
{
    protected $primaryKey = 'area_id'; // ✅ FIX
    public $incrementing = true;
    protected $keyType = 'int';
    
    protected $fillable = ['area_name', 'description'];
    
    public function datasets(): HasMany
    {
        return $this->hasMany(Dataset::class, 'subject_area_id', 'area_id');
    }
}