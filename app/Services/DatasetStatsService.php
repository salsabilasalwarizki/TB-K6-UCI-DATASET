<?php

namespace App\Services;

use App\Models\Dataset;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DatasetStatsService
{
    /**
     * Get comprehensive dataset statistics
     */
    public function getStats(bool $fresh = false): array
    {
        $key = 'dataset:stats:global';
        
        return Cache::remember($key, config('datasets.cache.stats_ttl', 7200), function() {
            $baseQuery = Dataset::where('status', 'available');
            
            return [
                'total' => $baseQuery->count(),
                'by_data_type' => $baseQuery->selectRaw('data_type, count(*) as count')
                    ->groupBy('data_type')->pluck('count', 'data_type')->toArray(),
                'by_task_type' => $baseQuery->selectRaw('task_type, count(*) as count')
                    ->groupBy('task_type')->pluck('count', 'task_type')->toArray(),
                'by_subject' => $baseQuery->selectRaw('subject_area, count(*) as count')
                    ->whereNotNull('subject_area')->groupBy('subject_area')
                    ->orderByDesc('count')->take(10)->pluck('count', 'subject_area')->toArray(),
                'instances' => [
                    'min' => $baseQuery->min('num_instances') ?? 0,
                    'max' => $baseQuery->max('num_instances') ?? 0,
                    'avg' => round($baseQuery->avg('num_instances') ?? 0, 2),
                ],
                'features' => [
                    'min' => $baseQuery->min('num_features') ?? 0,
                    'max' => $baseQuery->max('num_features') ?? 0,
                    'avg' => round($baseQuery->avg('num_features') ?? 0, 2),
                ],
                'recent' => $baseQuery->latest('created_at')->take(5)
                    ->get(['dataset_id', 'name', 'slug', 'created_at']),
                'popular' => $baseQuery->orderByDesc('view_count')->take(5)
                    ->get(['dataset_id', 'name', 'slug', 'view_count']),
            ];
        });
    }
    
    /**
     * Get stats for specific filter combination
     */
    public function getFilteredStats(array $filters): array
    {
        $query = Dataset::where('status', 'available');
        
        // Apply filters (simplified - extend as needed)
        if (!empty($filters['data_type'])) {
            $query->where('data_type', $filters['data_type']);
        }
        if (!empty($filters['subject_area'])) {
            $query->whereIn('subject_area', (array) $filters['subject_area']);
        }
        
        return [
            'count' => $query->count(),
            'instances_range' => [
                'min' => $query->min('num_instances') ?? 0,
                'max' => $query->max('num_instances') ?? 0,
            ],
            'features_range' => [
                'min' => $query->min('num_features') ?? 0,
                'max' => $query->max('num_features') ?? 0,
            ],
        ];
    }
    
    /**
     * Clear all dataset-related caches
     */
    public function clearCache(): void
    {
        Cache::forget('dataset:stats:global');
        Cache::forget('dataset_filters');
        // Add more cache keys as needed
    }
}