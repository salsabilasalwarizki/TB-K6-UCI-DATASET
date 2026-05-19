<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Person extends Model
{
    protected $table = 'people';
    protected $primaryKey = 'person_id';
    public $incrementing = true;
    
    protected $fillable = ['name', 'email', 'affiliation', 'orcid', 'profile_url'];
    
    public function datasets(): BelongsToMany
    {
        return $this->belongsToMany(
            Dataset::class,
            'dataset_contributors',
            'person_id',
            'dataset_id'
        )->withPivot('contribution_role', 'display_order', 'created_at');
    }
}