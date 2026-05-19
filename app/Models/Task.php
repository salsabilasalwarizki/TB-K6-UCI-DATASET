<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model {
    protected $primaryKey = 'task_id';
    protected $fillable = ['task_name', 'description'];
    protected $casts = ['task_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
    public function datasets(): HasMany { return $this->hasMany(Dataset::class, 'task_id', 'task_id'); }
}