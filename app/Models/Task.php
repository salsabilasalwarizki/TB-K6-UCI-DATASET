<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $primaryKey = 'task_id'; // ✅ FIX
    public $incrementing = true;
    protected $keyType = 'int';
    
    protected $fillable = ['task_name', 'description'];
    
    public function datasets(): HasMany
    {
        return $this->hasMany(Dataset::class, 'task_id', 'task_id');
    }
}