<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doi extends Model {
    protected $primaryKey = 'doi_id';
    protected $fillable = ['doi_string', 'resolution_url'];
    protected $casts = ['doi_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
    public function datasets(): HasMany { return $this->hasMany(Dataset::class, 'doi_id', 'doi_id'); }
}
