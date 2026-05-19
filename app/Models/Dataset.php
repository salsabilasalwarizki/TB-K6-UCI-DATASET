<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, BelongsToMany};
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class Dataset extends Model
{
    protected $primaryKey = 'dataset_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name', 'description', 'donated_date', 'last_updated',
        'characteristics', 'feature_type', 'num_instances', 'num_features',
        'has_missing_values', 'additional_info', 'attribute_info',
        'view_count', 'download_count', 'citation_count',
        'task_id', 'subject_area_id', 'license_id', 'doi_id',
        'status', 'approved_at', 'approved_by',
        'rejected_at', 'rejected_by', 'admin_notes',
        'user_id', 'slug', 'abstract', 'data_type', 'task_type',
        'dataset_url', 'linked_date', 'domain',
    ];

    protected $casts = [
        'donated_date' => 'date',
        'last_updated' => 'date',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'has_missing_values' => 'boolean',
        'additional_info' => 'array',
        'attribute_info' => 'array',
        'view_count' => 'integer',
        'download_count' => 'integer',
        'citation_count' => 'integer',
        'num_instances' => 'integer',
        'num_features' => 'integer',
    ];

    protected $hidden = ['approved_by', 'rejected_by', 'admin_notes'];
    protected $visible = [
        'dataset_id', 'name', 'description', 'donated_date', 'last_updated',
        'characteristics', 'feature_type', 'num_instances', 'num_features',
        'has_missing_values', 'view_count', 'download_count', 'citation_count',
        'task', 'subjectArea', 'license', 'doi', 'creators',
        'papers', 'files', 'variables', 'status',
    ];

    /* RELATIONSHIPS */
    public function task(): BelongsTo { return $this->belongsTo(Task::class, 'task_id', 'task_id'); }
    public function subjectArea(): BelongsTo { return $this->belongsTo(SubjectArea::class, 'subject_area_id', 'area_id'); }
    public function license(): BelongsTo { return $this->belongsTo(License::class, 'license_id', 'license_id'); }
    public function doi(): BelongsTo { return $this->belongsTo(Doi::class, 'doi_id', 'doi_id'); }

    public function variables(): HasMany {
        return $this->hasMany(Variable::class, 'dataset_id', 'dataset_id')->orderBy('display_order');
    }

    public function files(): BelongsToMany {
        return $this->belongsToMany(File::class, 'dataset_files', 'dataset_id', 'file_id')
            ->withPivot('file_role', 'is_default', 'display_order', 'created_at');
    }

    public function creators(): BelongsToMany {
        return $this->belongsToMany(Person::class, 'dataset_contributors', 'dataset_id', 'person_id')
            ->withPivot('contribution_role', 'display_order', 'created_at');
    }

    public function papers(): BelongsToMany {
        return $this->belongsToMany(Paper::class, 'dataset_papers', 'dataset_id', 'paper_id')
            ->withPivot('citation_type', 'is_primary', 'created_at');
    }

    // Keywords via JSON (tidak ada pivot table di schema)
    public function getKeywordsAttribute() {
        $additional = json_decode($this->additional_info ?? '{}', true);
        $names = $additional['keywords'] ?? [];
        return Keyword::whereIn('keyword_name', $names)->get();
    }

    /* ACCESSORS */
    protected function citation(): Attribute {
        return Attribute::make(get: fn() => $this->generateCitation());
    }
    private function generateCitation(): string {
        $creators = $this->creators->pluck('name')->join(', ');
        $year = $this->donated_date?->year ?? date('Y');
        $doiString = $this->doi?->doi_string;
        $doiLink = $doiString ? " https://doi.org/{$doiString}" : '';
        return "{$creators} ({$year}). {$this->name} [Dataset]. UCI Machine Learning Repository.{$doiLink}";
    }
    protected function donatedDateFormatted(): Attribute {
        return Attribute::make(get: fn() => $this->donated_date?->format('n/j/Y') ?? 'N/A');
    }
    protected function statusBadgeClass(): Attribute {
        return Attribute::make(get: fn() => match($this->status ?? 'pending') {
            'approved' => 'success', 'rejected' => 'danger', default => 'warning',
        });
    }
    protected function shortDescription(): Attribute {
        return Attribute::make(get: fn() => Str::limit($this->description, 200));
    }
    protected function slug(): Attribute {
        return Attribute::make(get: fn() => Str::slug($this->name));
    }

    /* SCOPES */
    public function scopePublished($query) { return $query->where('status', 'approved'); }
    public function scopePending($query) { return $query->where('status', 'pending'); }
    public function scopeRejected($query) { return $query->where('status', 'rejected'); }
    public function scopeSearch($query, ?string $keyword) {
        if (!$keyword) return $query;
        return $query->where(function($q) use ($keyword) {
            $q->where('name', 'LIKE', "%{$keyword}%")->orWhere('description', 'LIKE', "%{$keyword}%");
        });
    }
    public function scopeWithTask($query, $taskId) { return $taskId ? $query->where('task_id', $taskId) : $query; }
    public function scopeWithSubjectArea($query, $areaId) { return $areaId ? $query->where('subject_area_id', $areaId) : $query; }
    public function scopeWithDataType($query, ?string $dataType) { return $dataType ? $query->where('data_type', 'LIKE', "%{$dataType}%") : $query; }
    public function scopeWithInstancesRange($query, ?int $min, ?int $max) {
        if ($min !== null) $query->where('num_instances', '>=', $min);
        if ($max !== null) $query->where('num_instances', '<=', $max);
        return $query;
    }
    public function scopeWithFeaturesRange($query, ?int $min, ?int $max) {
        if ($min !== null) $query->where('num_features', '>=', $min);
        if ($max !== null) $query->where('num_features', '<=', $max);
        return $query;
    }
    public function scopeOrderByPopular($query, $dir = 'desc') { return $query->orderBy('view_count', $dir); }
    public function scopeOrderByDownloads($query, $dir = 'desc') { return $query->orderBy('download_count', $dir); }
    public function scopeOrderByCitations($query, $dir = 'desc') { return $query->orderBy('citation_count', $dir); }
    public function scopeOrderByRecent($query, $dir = 'desc') { return $query->orderBy('donated_date', $dir); }
    public function scopeOrderByName($query, $dir = 'asc') { return $query->orderBy('name', $dir); }

    /* HELPERS */
    public function getIconClass(): string {
        $name = strtolower($this->name);
        $type = strtolower($this->data_type ?? '');
        if (Str::contains($name, 'iris')) return 'bi-flower1';
        elseif (Str::contains($name, 'heart') || Str::contains($name, 'cardio')) return 'bi-heart-pulse';
        elseif (Str::contains($name, 'sound') || Str::contains($name, 'audio')) return 'bi-volume-up';
        elseif (Str::contains($name, 'image') || Str::contains($name, 'vision')) return 'bi-image';
        elseif (Str::contains($name, 'text') || Str::contains($name, 'nlp')) return 'bi-chat-square-text';
        elseif (Str::contains($name, 'time') || Str::contains($name, 'series')) return 'bi-graph-up';
        if (Str::contains($type, 'image')) return 'bi-image';
        elseif (Str::contains($type, 'text')) return 'bi-chat-square-text';
        elseif (Str::contains($type, 'time-series') || Str::contains($type, 'sequential')) return 'bi-graph-up';
        elseif (Str::contains($type, 'tabular') || Str::contains($type, 'multivariate')) return 'bi-table';
        return 'bi-database';
    }
    public function hasDownloadableFiles(): bool {
        return $this->files->contains(fn($f) => in_array(strtolower($f->file_format), ['csv','arff','txt','json','zip']));
    }
    public function getPrimaryFile() { return $this->files->firstWhere('is_default', true) ?? $this->files->first(); }
    public function getPrimaryFileSize(): string { $f = $this->getPrimaryFile(); return $f ? $f->file_size : 'N/A'; }
    public function isPublished(): bool { return $this->status === 'approved'; }
    public function isPending(): bool { return $this->status === 'pending'; }
    public function isRejected(): bool { return $this->status === 'rejected'; }
    public function getDescriptiveInfo(string $key, $default = null) {
        $add = $this->additional_info ?? [];
        $desc = is_array($add) ? ($add['descriptive'] ?? []) : [];
        return $desc[$key] ?? $default;
    }
    public function getFilesCount(): int { return $this->files->count(); }
    public function getVariablesCount(): int { return $this->variables->count(); }
    public function getDownloadUrl(): ?string { $f = $this->getPrimaryFile(); return $f ? route('datasets.download', [$this, $f]) : null; }
    public function incrementViewCount(): void { $this->increment('view_count'); }
    public function incrementDownloadCount(): void { $this->increment('download_count'); }
    public function incrementCitationCount(): void { $this->increment('citation_count'); }
    public function approvedByUser() { return $this->approved_by ? \App\Models\User::find($this->approved_by) : null; }
    public function rejectedByUser() { return $this->rejected_by ? \App\Models\User::find($this->rejected_by) : null; }
    public function isCreator(\App\Models\User $user): bool {
        return $this->user_id === $user->id || $this->creators->contains(fn($c) => $c->email === $user->email || $c->name === $user->name);
    }
    public function getUrl(): string { return route('datasets.show', $this); }
    public function getEditUrl(): ?string { return null; }
    protected static function boot() {
        parent::boot();
        static::updating(fn($d) => $d->last_updated = now());
    }
}