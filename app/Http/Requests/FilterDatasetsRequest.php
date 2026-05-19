<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterDatasetsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'search' => 'nullable|string|max:255',
            'keywords' => 'nullable|array',
            'keywords.*' => 'integer|exists:keywords,keyword_id',
            'data_type' => 'nullable|in:' . implode(',', config('datasets.filters.data_types')),
            'task_type' => 'nullable|in:' . implode(',', config('datasets.filters.task_types')),
            'subject_area' => 'nullable|array',
            'subject_area.*' => 'string|max:100',
            'domain' => 'nullable|array',
            'domain.*' => 'string|max:100',
            'variable_types' => 'nullable|array',
            'variable_types.*' => 'in:' . implode(',', config('datasets.filters.variable_types')),
            'has_missing' => 'nullable|boolean',
            'status' => 'nullable|array',
            'status.*' => 'in:' . implode(',', config('datasets.filters.statuses')),
            'instances_min' => 'nullable|integer|min:0',
            'instances_max' => 'nullable|integer|min:0|gte:instances_min',
            'features_min' => 'nullable|integer|min:0',
            'features_max' => 'nullable|integer|min:0|gte:features_min',
            'sort' => 'nullable|in:name,num_instances,num_features,view_count,download_count,citation_count,donated_date,created_at',
            'order' => 'nullable|in:asc,desc',
            'page' => 'nullable|integer|min:1',
        ];
    }
    
    public function messages(): array
    {
        return [
            'data_type.in' => 'Invalid data type selected.',
            'task_type.in' => 'Invalid task type selected.',
            'instances_max.gte' => 'Max instances must be greater than or equal to min.',
            'features_max.gte' => 'Max features must be greater than or equal to min.',
        ];
    }
}