<?php

namespace App\Http\Controllers;

use App\Models\{
    Dataset, DatasetDescription, File, Keyword, Person, 
    Variable, VariableCategory, Paper, Doi, License, Image
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ContributeController extends Controller
{
    /**
     * Session key for donation wizard data
     */
    protected string $sessionKey = 'donation_wizard';
    
    /**
     * Valid enum values from config
     */
    protected array $validDataTypes = [
        'Multivariate', 'Text', 'Image', 'Time-Series', 'Sequential', 
        'Tabular', 'Relational', 'Domain-Theory', 'Data-Generator', 'Univariate', 'Spatiotemporal', 'Other'
    ];
    
    protected array $validTaskTypes = [
        'Classification', 'Regression', 'Clustering', 
        'Causal-Discovery', 'Relational-Learning', 'Other'
    ];
    
    protected array $validVariableTypes = [
        'Categorical', 'Integer', 'Real', 'Text', 'Binary', 'Ordinal', 'Nominal', 'DateTime'
    ];
    
    protected array $validStatuses = [
        'pending', 'approved', 'rejected', 'available', 'deprecated'
    ];

    /**
     * Show donation policy page (gate before form)
     */
    public function policy()
    {
        return view('contribute.policy');
    }

    // ===== PAGE 1: METADATA =====
    
    public function createMetadata(Request $request)
    {
        $data = session($this->sessionKey, []);
        return view('contribute.metadata', [
            'old' => $data['metadata'] ?? [],
            'step' => 1
        ]);
    }
    
    public function storeMetadata(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'abstract' => 'required|string|max:2000',
            'num_instances' => 'required|integer|min:0',
            'num_features' => 'nullable|integer|min:0',
            'doi' => 'nullable|string|regex:/^10\.\d{4,}\/.+$/|max:255',
            'characteristics' => 'required|array|min:1',
            'characteristics.*' => ['required', Rule::in($this->validDataTypes)],
            'subject_area' => ['required', 'string', 'max:100'],
            'associated_tasks' => 'nullable|array',
            'associated_tasks.*' => ['required', Rule::in($this->validTaskTypes)],
            'feature_types' => 'nullable|array',
            'feature_types.*' => ['required', Rule::in($this->validVariableTypes)],
            'graphics' => 'nullable|image|max:5120', // 5MB max
        ], [
            'name.required' => 'Dataset name is required',
            'abstract.required' => 'Abstract is required',
            'abstract.max' => 'Abstract must not exceed 2000 characters',
            'num_instances.required' => 'Number of instances is required',
            'characteristics.required' => 'Please select at least one dataset characteristic',
            'subject_area.required' => 'Please select a subject area',
        ]);
        
        // Handle file upload
        $graphicsPath = null;
        if ($request->hasFile('graphics')) {
            $file = $request->file('graphics');
            $graphicsPath = $file->store('uploads/datasets/graphics', 'public');
        }
        
        // Save to session
        $data = session($this->sessionKey, []);
        $data['metadata'] = array_merge($data['metadata'] ?? [], [
            'name' => $validated['name'],
            'abstract' => $validated['abstract'],
            'num_instances' => $validated['num_instances'],
            'num_features' => $validated['num_features'] ?? null,
            'doi' => $validated['doi'] ?? null,
            'characteristics' => $validated['characteristics'],
            'subject_area' => $validated['subject_area'],
            'associated_tasks' => $validated['associated_tasks'] ?? [],
            'feature_types' => $validated['feature_types'] ?? [],
            'graphics_path' => $graphicsPath,
        ]);
        
        session([$this->sessionKey => $data]);
        
        return redirect()->route('contribute.paper');
    }

    // ===== PAGE 2: PAPER =====
    
    public function createPaper(Request $request)
    {
        if (!session($this->sessionKey . '.metadata')) {
            return redirect()->route('contribute.metadata')->with('error', 'Please complete metadata first');
        }
        
        $data = session($this->sessionKey, []);
        return view('contribute.paper', [
            'oldPaper' => $data['paper'] ?? [],
            'step' => 2
        ]);
    }
    
    public function storePaper(Request $request)
    {
        $validated = $request->validate([
            'paper_id_type' => 'nullable|in:None,DOI,arXiv,PubMed',
            'paper_id' => 'nullable|string|max:255',
            'title' => 'required|string|max:500',
            'authors' => 'required|string|max:500',
            'venue' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'url' => 'nullable|url|max:500',
        ], [
            'title.required' => 'Paper title is required',
            'authors.required' => 'Authors field is required',
            'venue.required' => 'Venue is required',
            'year.required' => 'Publication year is required',
        ]);
        
        $data = session($this->sessionKey, []);
        $data['paper'] = array_merge($data['paper'] ?? [], [
            'paper_id_type' => $validated['paper_id_type'] ?? 'None',
            'paper_id' => $validated['paper_id'] ?? null,
            'title' => $validated['title'],
            'authors' => $validated['authors'],
            'venue' => $validated['venue'],
            'year' => $validated['year'],
            'url' => $validated['url'] ?? null,
        ]);
        
        session([$this->sessionKey => $data]);
        
        return redirect()->route('contribute.creators');
    }

    // ===== PAGE 3: CREATORS =====
    
    public function createCreators(Request $request)
    {
        if (!session($this->sessionKey . '.metadata')) {
            return redirect()->route('contribute.metadata');
        }
        
        $data = session($this->sessionKey, []);
        return view('contribute.creators', [
            'creators' => $data['creators'] ?? [['name' => '', 'email' => '', 'affiliation' => '', 'contribution_role' => 'Creator']],
            'step' => 3
        ]);
    }
    
    public function storeCreators(Request $request)
    {
        $validated = $request->validate([
            'creators' => 'required|array|min:1',
            'creators.*.name' => 'required|string|max:255',
            'creators.*.email' => 'nullable|email|max:255',
            'creators.*.affiliation' => 'nullable|string|max:255',
            'creators.*.contribution_role' => 'required|in:Creator,Donor,Analyst,Data Collector,Other',
            'creators.*.orcid' => 'nullable|string|regex:/^\d{4}-\d{4}-\d{4}-\d{4}$/|max:50',
        ], [
            'creators.*.name.required' => 'Creator name is required',
            'creators.*.contribution_role.required' => 'Please select a contribution role',
        ]);
        
        $data = session($this->sessionKey, []);
        $data['creators'] = $validated['creators'];
        
        session([$this->sessionKey => $data]);
        
        return redirect()->route('contribute.files');
    }

    // ===== PAGE 4: FILES =====
    
    public function createFiles(Request $request)
    {
        if (!session($this->sessionKey . '.metadata')) {
            return redirect()->route('contribute.metadata');
        }
        
        $data = session($this->sessionKey, []);
        return view('contribute.files', [
            'files' => $data['files'] ?? [],
            'step' => 4
        ]);
    }
    
    public function storeFiles(Request $request)
    {
        $validated = $request->validate([
            'files' => 'required|array|min:1',
            'files.*.filename' => 'required|string|max:255',
            'files.*.file_format' => 'required|string|max:20',
            'files.*.file_role' => 'required|in:data,documentation,code,example,test,other',
            'files.*.is_default' => 'nullable|boolean',
            'files.*.file' => 'nullable|file|max:524288', // 500MB max per file
        ]);
        
        $uploadedFiles = [];
        
        foreach ($validated['files'] as $index => $fileData) {
            $entry = [
                'original_filename' => $fileData['filename'],
                'file_format' => strtolower($fileData['file_format']),
                'file_role' => $fileData['file_role'],
                'is_default' => !empty($fileData['is_default']),
                'display_order' => $index,
            ];
            
            // Handle actual file upload
            if ($request->hasFile("files.{$index}.file")) {
                $file = $request->file("files.{$index}.file");
                $path = $file->store('uploads/datasets/data', 'public');
                
                $entry['file_path'] = $path;
                $entry['file_size_bytes'] = $file->getSize();
                $entry['mime_type'] = $file->getMimeType();
                $entry['checksum_md5'] = md5_file($file->getRealPath());
                $entry['checksum_sha256'] = hash_file('sha256', $file->getRealPath());
            }
            
            $uploadedFiles[] = $entry;
        }
        
        $data = session($this->sessionKey, []);
        $data['files'] = $uploadedFiles;
        
        session([$this->sessionKey => $data]);
        
        return redirect()->route('contribute.keywords');
    }

    // ===== PAGE 5: KEYWORDS =====
    
    public function createKeywords(Request $request)
{
    if (!session($this->sessionKey . '.metadata')) {
        return redirect()->route('contribute.metadata')->with('error', 'Please complete metadata first');
    }
    
    $data = session($this->sessionKey, []);
    $existingKeywords = $data['keywords'] ?? [];
    
    // Get popular keywords for suggestions - FIX: Use subquery or join to count usage
    $popularKeywords = Keyword::withCount('datasets')
        ->orderBy('datasets_count', 'desc')
        ->take(20)
        ->get();
    
    return view('contribute.keywords', [
        'keywords' => $existingKeywords,
        'popularKeywords' => $popularKeywords,
        'step' => 5
    ]);
}
    
    // app/Http/Controllers/ContributeController.php

public function storeKeywords(Request $request)
{
    $validated = $request->validate([
        'keywords' => 'nullable|array',  // ← Pastikan array
        'keywords.*' => 'required|string|max:100|min:2',
        'new_keywords' => 'nullable|string|max:500',
    ]);
    
    $keywords = $validated['keywords'] ?? [];
    
    // Parse comma-separated new keywords
    if (!empty($validated['new_keywords'])) {
        $newKws = array_filter(array_map('trim', explode(',', $validated['new_keywords'])));
        $keywords = array_merge($keywords, $newKws);
    }
    
    // Remove duplicates and empty values
    $keywords = array_values(array_unique(array_filter($keywords)));
    
    $data = session($this->sessionKey, []);
    
    // ✅ PENTING: Simpan sebagai array, BUKAN JSON string
    $data['keywords'] = $keywords;  // ← Array, bukan json_encode()
    
    session([$this->sessionKey => $data]);
    
    return redirect()->route('contribute.variable-info');
}

    // ===== PAGE 6: VARIABLE INFO =====
    
    public function createVariableInfo(Request $request)
    {
        if (!session($this->sessionKey . '.metadata')) {
            return redirect()->route('contribute.metadata');
        }
        
        $data = session($this->sessionKey, []);
        return view('contribute.variable-info', [
            'variables' => $data['variables'] ?? [],
            'validTypes' => $this->validVariableTypes,
            'validRoles' => ['feature', 'target', 'id', 'metadata', 'other'],
            'step' => 6
        ]);
    }
    
    public function storeVariableInfo(Request $request)
    {
        $validated = $request->validate([
            'variables' => 'nullable|array',
            'variables.*.variable_name' => 'required|string|max:100',
            'variables.*.display_name' => 'nullable|string|max:100',
            'variables.*.role' => 'required|in:feature,target,id,metadata,other',
            'variables.*.variable_type' => ['required', Rule::in($this->validVariableTypes)],
            'variables.*.description' => 'nullable|string|max:500',
            'variables.*.unit' => 'nullable|string|max:50',
            'variables.*.min_value' => 'nullable|numeric',
            'variables.*.max_value' => 'nullable|numeric|gte:variables.*.min_value',
            'variables.*.categories' => 'nullable|string|max:1000',
        ], [
            'variables.*.variable_name.required' => 'Variable name is required',
            'variables.*.role.required' => 'Please select a variable role',
            'variables.*.variable_type.in' => 'Invalid variable type selected',
        ]);
        
        $variables = [];
        
        if (!empty($validated['variables'])) {
            foreach ($validated['variables'] as $index => $var) {
                $entry = [
                    'variable_name' => $var['variable_name'],
                    'display_name' => $var['display_name'] ?? $var['variable_name'],
                    'role' => $var['role'],
                    'variable_type' => $var['variable_type'],
                    'description' => $var['description'] ?? null,
                    'unit' => $var['unit'] ?? null,
                    'min_value' => $var['min_value'] ?? null,
                    'max_value' => $var['max_value'] ?? null,
                    'display_order' => $index,
                    'is_visible' => true,
                ];
                
                // Parse categories for categorical variables
                if ($var['variable_type'] === 'Categorical' && !empty($var['categories'])) {
                    $entry['categories'] = array_filter(array_map('trim', explode(',', $var['categories'])));
                }
                
                $variables[] = $entry;
            }
        }
        
        $data = session($this->sessionKey, []);
        $data['variables'] = $variables;
        
        session([$this->sessionKey => $data]);
        
        return redirect()->route('contribute.descriptive');
    }

    // ===== PAGE 7: DESCRIPTIVE & SUBMIT =====
    
    public function createDescriptive(Request $request)
    {
        if (!session($this->sessionKey . '.metadata')) {
            return redirect()->route('contribute.metadata');
        }
        
        $data = session($this->sessionKey, []);
        
        // Build summary for review
        $summary = [
            'name' => $data['metadata']['name'] ?? '',
            'abstract' => $data['metadata']['abstract'] ?? '',
            'characteristics' => implode(', ', $data['metadata']['characteristics'] ?? []),
            'subject_area' => $data['metadata']['subject_area'] ?? '',
            'num_instances' => $data['metadata']['num_instances'] ?? 0,
            'num_features' => $data['metadata']['num_features'] ?? 0,
            'paper_title' => $data['paper']['title'] ?? 'None',
            'creators_count' => count($data['creators'] ?? []),
            'files_count' => count($data['files'] ?? []),
            'keywords_count' => count($data['keywords'] ?? []),
            'variables_count' => count($data['variables'] ?? []),
        ];
        
        return view('contribute.descriptive', [
            'descriptive' => $data['descriptive'] ?? [],
            'summary' => $summary,
            'step' => 7
        ]);
    }
    
    public function submitDonation(Request $request)
    {
        $validated = $request->validate([
            'purpose' => 'nullable|string|max:2000',
            'funding' => 'nullable|string|max:500',
            'instances_represent' => 'nullable|string|max:2000',
            'data_splits' => 'nullable|string|max:1000',
            'sensitive_data' => 'nullable|string|max:1000',
            'preprocessing' => 'nullable|string|max:2000',
            'additional_info' => 'nullable|string|max:5000',
            'citation_requests' => 'nullable|string|max:1000',
            'agree_license' => 'required|accepted',
            'agree_terms' => 'required|accepted',
        ], [
            'agree_license.accepted' => 'You must agree to the CC BY 4.0 license',
            'agree_terms.accepted' => 'You must agree to the terms of service',
        ]);
        
        // Save descriptive data to session
        $data = session($this->sessionKey, []);
        $data['descriptive'] = $validated;
        session([$this->sessionKey => $data]);
        
        // ===== FINAL SUBMISSION: Create all database records =====
        try {
            DB::beginTransaction();
            
            // 1. Create or get License
            $license = License::firstOrCreate(
                ['license_name' => 'Creative Commons Attribution 4.0 International'],
                [
                    'description' => 'This allows for the sharing and adaptation of the datasets for any purpose, provided that the appropriate credit is given.',
                    'license_url' => 'https://creativecommons.org/licenses/by/4.0/',
                ]
            );
            
            // 2. Create or get DOI
            $doiId = null;
            if (!empty($data['metadata']['doi'])) {
                $doi = Doi::firstOrCreate(
                    ['doi_string' => $data['metadata']['doi']],
                    ['resolution_url' => "https://doi.org/{$data['metadata']['doi']}"]
                );
                $doiId = $doi->doi_id;
            }
            
            // 3. Create Dataset
            $metadata = $data['metadata'];
            $dataset = Dataset::create([
                // Identifiers
                'slug' => Str::slug($metadata['name']),
                'name' => $metadata['name'],
                'display_name' => $metadata['name'],
                
                // Descriptions
                'description' => $metadata['abstract'],
                'abstract' => $metadata['abstract'],
                
                // Numeric
                'num_instances' => $metadata['num_instances'],
                'num_features' => $metadata['num_features'] ?? null,
                
                // Enum fields - use first selected value
                'data_type' => $metadata['characteristics'][0] ?? 'Other',
                'task_type' => !empty($metadata['associated_tasks']) ? $metadata['associated_tasks'][0] : null,
                
                // String fields
                'subject_area' => $metadata['subject_area'],
                
                // Status & dates
                'status' => 'pending', // Requires admin approval
                'donated_date' => now(),
                
                // Boolean
                'has_missing_values' => false, // Can be updated later
                
                // Foreign keys
                'user_id' => auth()->id(),
                'license_id' => $license->license_id,
                'doi_id' => $doiId,
            ]);
            
            // 4. Create Dataset Description
            if (!empty($data['descriptive'])) {
                DatasetDescription::create([
                    'dataset_id' => $dataset->dataset_id,
                    'purpose' => $data['descriptive']['purpose'] ?? null,
                    'funding' => $data['descriptive']['funding'] ?? null,
                    'instances_represent' => $data['descriptive']['instances_represent'] ?? null,
                    'data_splits' => $data['descriptive']['data_splits'] ?? null,
                    'sensitive_data' => $data['descriptive']['sensitive_data'] ?? null,
                    'preprocessing' => $data['descriptive']['preprocessing'] ?? null,
                    'additional_info' => $data['descriptive']['additional_info'] ?? null,
                    'citation_requests' => $data['descriptive']['citation_requests'] ?? null,
                ]);
            }
            
            // 5. Attach Keywords
            if (!empty($data['keywords'])) {
                foreach ($data['keywords'] as $kwName) {
                    $keyword = Keyword::firstOrCreate(
                        ['keyword_name' => $kwName],
                        ['slug' => Str::slug($kwName)]
                    );
                    $dataset->keywords()->attach($keyword->keyword_id);
                }
            }
            
            // 6. Create/Attach Creators
            if (!empty($data['creators'])) {
                foreach ($data['creators'] as $index => $creatorData) {
                    $person = Person::firstOrCreate(
                        [
                            'name' => $creatorData['name'],
                            'email' => $creatorData['email'] ?? null,
                        ],
                        ['affiliation' => $creatorData['affiliation'] ?? null]
                    );
                    
                    $dataset->contributors()->attach($person->person_id, [
                        'contribution_role' => strtolower($creatorData['contribution_role']),
                        'display_order' => $index,
                    ]);
                }
            }
            
            // 7. Create Files
            if (!empty($data['files'])) {
                foreach ($data['files'] as $index => $fileData) {
                    $file = File::create([
                        'filename' => $fileData['original_filename'],
                        'original_filename' => $fileData['original_filename'],
                        'file_path' => $fileData['file_path'] ?? null,
                        'file_size_bytes' => $fileData['file_size_bytes'] ?? null,
                        'mime_type' => $fileData['mime_type'] ?? null,
                        'file_format' => $fileData['file_format'],
                        'description' => null,
                        'checksum_md5' => $fileData['checksum_md5'] ?? null,
                        'checksum_sha256' => $fileData['checksum_sha256'] ?? null,
                    ]);
                    
                    $dataset->files()->attach($file->file_id, [
                        'file_role' => $fileData['file_role'],
                        'is_default' => $fileData['is_default'] ?? ($index === 0),
                        'display_order' => $index,
                    ]);
                }
            }
            
            // 8. Create Variables
            if (!empty($data['variables'])) {
                foreach ($data['variables'] as $index => $varData) {
                    $variable = Variable::create([
                        'dataset_id' => $dataset->dataset_id,
                        'variable_name' => $varData['variable_name'],
                        'display_name' => $varData['display_name'],
                        'role' => $varData['role'],
                        'variable_type' => $varData['variable_type'],
                        'description' => $varData['description'] ?? null,
                        'unit' => $varData['unit'] ?? null,
                        'min_value' => $varData['min_value'] ?? null,
                        'max_value' => $varData['max_value'] ?? null,
                        'missing_count' => 0,
                        'unique_count' => null,
                        'display_order' => $index,
                        'is_visible' => true,
                    ]);
                    
                    // Create categories for categorical variables
                    if ($varData['variable_type'] === 'Categorical' && !empty($varData['categories'])) {
                        foreach ($varData['categories'] as $catIndex => $catValue) {
                            VariableCategory::create([
                                'variable_id' => $variable->variable_id,
                                'category_value' => $catValue,
                                'category_label' => $catValue,
                                'display_order' => $catIndex,
                            ]);
                        }
                    }
                }
            }
            
            // 9. Create/Attach Paper
            if (!empty($data['paper']['title'])) {
                $paper = Paper::firstOrCreate(
                    [
                        'title' => $data['paper']['title'],
                        'doi' => $data['paper']['paper_id_type'] === 'DOI' ? $data['paper']['paper_id'] : null,
                    ],
                    [
                        'authors' => $data['paper']['authors'] ?? '',
                        'venue' => $data['paper']['venue'] ?? '',
                        'publication_year' => $data['paper']['year'] ?? date('Y'),
                        'url' => $data['paper']['url'] ?? null,
                    ]
                );
                
                $dataset->papers()->attach($paper->paper_id, [
                    'citation_type' => 'introductory',
                    'is_primary' => true,
                ]);
            }
            
            // 10. Handle graphics/thumbnail upload
            if (!empty($metadata['graphics_path'])) {
                $image = Image::create([
                    'filename' => basename($metadata['graphics_path']),
                    'original_filename' => basename($metadata['graphics_path']),
                    'file_path' => $metadata['graphics_path'],
                    'file_size_bytes' => Storage::disk('public')->size($metadata['graphics_path']),
                    'mime_type' => Storage::disk('public')->mimeType($metadata['graphics_path']),
                    'alt_text' => $dataset->name,
                    'image_type' => 'thumbnail',
                ]);
                
                $dataset->images()->attach($image->image_id, [
                    'role' => 'thumbnail',
                    'is_primary' => true,
                    'display_order' => 0,
                ]);
                
                // Also update dataset thumbnail_url
                $dataset->thumbnail_url = Storage::url($metadata['graphics_path']);
                $dataset->saveQuietly();
            }
            
            DB::commit();
            
            // Clear session
            session()->forget($this->sessionKey);
            
            // Redirect to success page
            return redirect()->route('profile.datasets')
                ->with('success', 'Dataset submitted successfully! It is now pending review.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log error
            \Log::error('Dataset donation failed: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'dataset_name' => $data['metadata']['name'] ?? 'Unknown',
            ]);
            
            return back()->withInput()->withErrors([
                'submission' => 'Failed to submit dataset: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Clear donation session (for cancel or timeout)
     */
    public function cancel(Request $request)
    {
        session()->forget($this->sessionKey);
        return redirect()->route('home')->with('info', 'Donation cancelled');
    }
    
    /**
     * API: Fetch paper metadata by ID (for auto-fill)
     */
    public function fetchPaperMetadata(Request $request)
    {
        $validated = $request->validate([
            'id_type' => 'required|in:DOI,arXiv,PubMed',
            'id_value' => 'required|string|max:255',
        ]);
        
        // Placeholder: Implement actual API calls to Crossref/arXiv/PubMed
        // For now, return mock data
        return response()->json([
            'success' => false,
            'message' => 'Auto-fill feature requires API integration. Please enter paper details manually.',
            // Example mock response:
            // 'data' => [
            //     'title' => 'Example Paper Title',
            //     'authors' => 'Smith, J.; Johnson, A.',
            //     'venue' => 'Journal of Machine Learning',
            //     'year' => 2024,
            //     'url' => 'https://example.com/paper',
            // ]
        ]);
    }
}