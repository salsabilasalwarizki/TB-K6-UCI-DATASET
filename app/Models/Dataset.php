<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo, HasMany, BelongsToMany, HasOne, MorphMany
};
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Dataset extends Model
{
    protected $primaryKey = 'dataset_id';
    protected $keyType = 'bigint';
    public $incrementing = true;
    
    protected $table = 'datasets';
    
    protected $fillable = [
        'uci_id', 'slug', 'name', 'display_name', 'description', 'abstract', 'summary',
        'num_instances', 'num_features', 'num_classes', 'file_size', 'file_size_bytes',
        'data_type', 'task_type', 'subject_area', 'domain', 'dataset_url', 'detail_url',
        'thumbnail_url', 'large_image_url', 'status', 'donated_date', 'linked_date',
        'view_count', 'download_count', 'citation_count', 'has_missing_values',
        'user_id', 'license_id', 'doi_id', 'approved_at', 'approved_by',
        'rejected_at', 'rejected_by', 'admin_notes'
    ];
    
    protected $casts = [
        'dataset_id' => 'integer',
        'num_instances' => 'integer',
        'num_features' => 'integer',
        'num_classes' => 'integer',
        'file_size_bytes' => 'integer',
        'view_count' => 'integer',
        'download_count' => 'integer',
        'citation_count' => 'integer',
        'has_missing_values' => 'boolean',
        'donated_date' => 'date',
        'linked_date' => 'date',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    protected $appends = ['url', 'thumbnail'];
    
    // === RELATIONSHIPS ===
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class, 'license_id');
    }
    
    public function doi(): BelongsTo
    {
        return $this->belongsTo(Doi::class, 'doi_id');
    }
    

// ✅ Relationship yang benar - gunakan nama unik
public function descriptionDetails(): HasOne
{
    return $this->hasOne(
        DatasetDescription::class, 
        'dataset_id', 
        'dataset_id'
    );
}
    
    public function descriptions()
    {
        // Helper accessor for blade compatibility
        return $this->description;
    }
    
// app/Models/Dataset.php

public function files(): BelongsToMany
{
    return $this->belongsToMany(
        File::class, 
        'dataset_files', 
        'dataset_id', 
        'file_id'
    )
    ->withPivot('file_role', 'is_default', 'display_order', 'created_at')
    // ✅ Gunakan orderByPivot atau nama tabel langsung
    ->orderByPivot('display_order')
    ->orderByPivot('is_default', 'desc');
}
    
public function images(): BelongsToMany
{
    return $this->belongsToMany(
        Image::class, 
        'dataset_images', 
        'dataset_id', 
        'image_id'
    )
    ->withPivot('role', 'display_order', 'is_primary', 'created_at')
    ->orderByPivot('display_order')  // ✅ Gunakan orderByPivot, bukan orderBy('pivot.display_order')
    ->orderByPivot('is_primary', 'desc');
}
    
    public function variables(): HasMany
    {
        return $this->hasMany(Variable::class, 'dataset_id', 'dataset_id')
            ->where('is_visible', true)
            ->orderBy('display_order');
    }
    
// ✅ keywords() - tidak perlu ordering khusus
public function keywords(): BelongsToMany
{
    return $this->belongsToMany(Keyword::class, 'dataset_keywords', 'dataset_id', 'keyword_id')
        ->withTimestamps();
}
/**
 * Papers relationship
 */
public function papers(): BelongsToMany
{
    return $this->belongsToMany(
        Paper::class, 
        'dataset_papers', 
        'dataset_id', 
        'paper_id'
    )
    ->withPivot('citation_type', 'is_primary', 'created_at')
    // ✅ BENAR: Gunakan orderByPivot() atau nama tabel langsung
    ->orderByPivot('is_primary', 'desc')
    ->orderByPivot('created_at', 'desc');
    // ❌ JANGAN pakai: ->orderBy('pivot.is_primary', 'desc')
}
// ✅ contributors() - pastikan benar
public function contributors(): BelongsToMany
{
    return $this->belongsToMany(Person::class, 'dataset_contributors', 'dataset_id', 'person_id')
        ->withPivot('contribution_role', 'display_order', 'created_at')
        ->orderByPivot('display_order');
}
    
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'dataset_id', 'dataset_id')
            ->whereHas('user', fn($q) => $q->where('is_active', true))
            ->orderBy('created_at', 'desc');
    }
    
    public function downloads(): HasMany
    {
        return $this->hasMany(Download::class, 'dataset_id', 'dataset_id');
    }
    
    // === ACCESSORS ===
    
// app/Models/Dataset.php

/**
 * Accessor untuk mendapatkan URL lengkap dataset
 */
public function getUrlAttribute(): string
{
    // Cek apakah dataset sudah punya ID (sudah tersimpan di database)
    if (empty($this->dataset_id)) {
        return ''; // Kembalikan string kosong jika ID belum ada
    }

    // Gunakan array eksplisit ['dataset' => ...] untuk memastikan parameter terpenuhi
    return route('datasets.show', ['dataset' => $this->dataset_id]);
}
    public function getThumbnailAttribute(): ?string
    {
        if ($this->thumbnail_url) return $this->thumbnail_url;
        
        $primaryImage = $this->images->where('pivot.is_primary', true)->first();
        if ($primaryImage) {
            return Storage::url($primaryImage->file_path);
        }
        
        return null;
    }
    
    public function getDefaultFile(): ?File
    {
        return $this->files->where('pivot.is_default', true)->first() 
            ?? $this->files->first();
    }
    
    // === SCOPES FOR FILTERING ===
    
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'available');
    }
    
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!$search) return $query;
        
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('display_name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('abstract', 'like', "%{$search}%")
              ->orWhere('subject_area', 'like', "%{$search}%")
              ->orWhere('domain', 'like', "%{$search}%");
        });
    }
    
    public function scopeFilterByDataType(Builder $query, ?string $dataType): Builder
    {
        return $dataType ? $query->where('data_type', $dataType) : $query;
    }
    
    public function scopeFilterByTaskType(Builder $query, ?string $taskType): Builder
    {
        return $taskType ? $query->where('task_type', $taskType) : $query;
    }
    
    public function scopeFilterBySubjectArea(Builder $query, ?array $areas): Builder
    {
        if (!$areas || empty($areas)) return $query;
        
        return $query->whereIn('subject_area', $areas);
    }
    
    public function scopeFilterByDomain(Builder $query, ?array $domains): Builder
    {
        if (!$domains || empty($domains)) return $query;
        
        return $query->whereIn('domain', $domains);
    }
    
    public function scopeFilterByKeywords(Builder $query, ?array $keywordIds): Builder
    {
        if (!$keywordIds || empty($keywordIds)) return $query;
        
        return $query->whereHas('keywords', function($q) use ($keywordIds) {
            $q->whereIn('keywords.keyword_id', $keywordIds);
        });
    }
    
    public function scopeFilterByInstances(Builder $query, ?int $min, ?int $max): Builder
    {
        if ($min !== null) $query->where('num_instances', '>=', $min);
        if ($max !== null) $query->where('num_instances', '<=', $max);
        return $query;
    }
    
    public function scopeFilterByFeatures(Builder $query, ?int $min, ?int $max): Builder
    {
        if ($min !== null) $query->where('num_features', '>=', $min);
        if ($max !== null) $query->where('num_features', '<=', $max);
        return $query;
    }
    
    public function scopeFilterByVariableTypes(Builder $query, ?array $types): Builder
    {
        if (!$types || empty($types)) return $query;
        
        return $query->whereHas('variables', function($q) use ($types) {
            $q->whereIn('variable_type', $types);
        });
    }
    
    public function scopeFilterByHasMissing(Builder $query, ?bool $hasMissing): Builder
    {
        if ($hasMissing === null) return $query;
        return $query->where('has_missing_values', $hasMissing);
    }
    
    public function scopeFilterByStatus(Builder $query, ?array $statuses): Builder
    {
        if (!$statuses || empty($statuses)) return $query->where('status', 'available');
        return $query->whereIn('status', $statuses);
    }
    
    public function scopeSortBy(Builder $query, ?string $sort, ?string $order): Builder
    {
        $order = strtolower($order) === 'asc' ? 'asc' : 'desc';
        
        return match ($sort) {
            'name' => $query->orderBy('name', $order),
            'num_instances' => $query->orderBy('num_instances', $order)->orderBy('name'),
            'num_features' => $query->orderBy('num_features', $order)->orderBy('name'),
            'donated_date' => $query->orderBy('donated_date', $order)->orderBy('name'),
            'created_at' => $query->orderBy('created_at', $order)->orderBy('name'),
            'download_count' => $query->orderBy('download_count', $order)->orderBy('name'),
            'citation_count' => $query->orderBy('citation_count', $order)->orderBy('name'),
            default => $query->orderBy('view_count', $order)->orderBy('name'),
        };
    }
    
    // === HELPER METHODS ===
    
    public function incrementView(): void
    {
        $this->increment('view_count');
    }
    
    public function incrementDownload(?File $file = null, ?User $user = null): void
    {
        $this->increment('download_count');
        
        Download::create([
            'dataset_id' => $this->dataset_id,
            'file_id' => $file?->file_id,
            'user_id' => $user?->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
    
    /**
 * Generate BibTeX citation string
 */
public function getCitationBibTeX(): string
{
    $authors = $this->contributors
        ->where('pivot.contribution_role', 'creator')
        ->pluck('name')
        ->join(', ');
    
    $authorString = !empty($authors) ? $authors : 'Dataset Contributors';
    $doiLine = $this->doi ? "  doi = {{$this->doi->doi_string}}," . PHP_EOL : '';
    $accessDate = date('Y-m-d');
    
    return "@dataset{$this->dataset_id}," . PHP_EOL .
           "  title = {{$this->name}}," . PHP_EOL .
           "  author = {{$authorString}}," . PHP_EOL .
           "  year = {{$this->created_at->year}}," . PHP_EOL .
           "  url = {{$this->url}}," . PHP_EOL .
           $doiLine .
           "  note = {Accessed: {$accessDate}}" . PHP_EOL .
           "}" . PHP_EOL;
}
// Di dalam class Dataset:

public function task(): BelongsTo
{
    return $this->belongsTo(Task::class, 'task_id', 'task_id');
}

public function subjectArea(): BelongsTo
{
    return $this->belongsTo(SubjectArea::class, 'subject_area_id', 'area_id');
}

// Accessor untuk backward compatibility dengan field lama:
public function getTaskTypeAttribute($value)
{
    // Prioritaskan relationship jika ada, fallback ke enum field
    return $this->task?->task_name ?? $value;
}

public function getSubjectAreaAttribute($value)
{
    return $this->subjectArea?->area_name ?? $value;
}
}