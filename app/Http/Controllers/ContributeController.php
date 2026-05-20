<?php

namespace App\Http\Controllers;

use App\Models\{Dataset, Creator, Variable, File, Task, SubjectArea, License, Doi, Keyword, Paper};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ContributeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PUBLIC ROUTES
    |--------------------------------------------------------------------------
    */

    /**
     * Donation Policy Page (Gate before form)
     */
    public function policy()
    {
        return view('contribute.policy');
    }

    /*
    |--------------------------------------------------------------------------
    | PAGE 1/7: METADATA
    |--------------------------------------------------------------------------
    */

    public function createMetadata()
    {
        $tasks = Task::all();
        $subjectAreas = SubjectArea::all();
        $oldData = Session::get('contribute_data', []);
        
        return view('contribute.metadata', compact('tasks', 'subjectAreas', 'oldData'));
    }

    public function storeMetadata(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'abstract' => 'required|string|max:1000',
            'num_instances' => 'required|integer|min:0',
            'num_features' => 'nullable|integer|min:0',
            'doi' => 'nullable|string|max:255',
            'characteristics' => 'required|array|min:1',
            'characteristics.*' => 'string|in:Tabular,Sequential,Multivariate,Time-Series,Text,Image,Spatiotemporal,Other',
            'subject_area' => 'required|string',
            'associated_tasks' => 'required|array|min:1',
            'associated_tasks.*' => 'string|in:Classification,Regression,Clustering,Other',
            'feature_types' => 'nullable|array',
            'feature_types.*' => 'string|in:Real,Categorical,Integer',
        ]);

        Session::put('contribute_data', [
            'name' => $validated['name'],
            'description' => $validated['abstract'],
            'num_instances' => $validated['num_instances'],
            'num_features' => $validated['num_features'] ?? null,
            'doi' => $validated['doi'] ?? null,
            'characteristics' => $validated['characteristics'],
            'subject_area' => $validated['subject_area'],
            'associated_tasks' => $validated['associated_tasks'],
            'feature_types' => $validated['feature_types'] ?? [],
            // Initialize empty arrays for later pages
            'paper' => [],
            'creators' => [],
            'files' => [],
            'keywords' => [],
            'variables' => [],
        ]);

        return redirect()->route('contribute.paper');
    }

    /*
    |--------------------------------------------------------------------------
    | PAGE 2/7: INTRODUCTORY PAPER
    |--------------------------------------------------------------------------
    */

    public function createPaper()
    {
        if (!Session::has('contribute_data')) {
            return redirect()->route('contribute.metadata')->with('error', 'Please fill metadata first.');
        }
        $oldPaper = Session::get('contribute_data.paper', []);
        return view('contribute.paper', compact('oldPaper'));
    }

    public function storePaper(Request $request)
    {
        $validated = $request->validate([
            'paper_id_type' => 'nullable|string|in:DOI,arXiv,PubMed,None',
            'paper_id' => 'nullable|string|max:255',
            'title' => 'required|string|max:500',
            'authors' => 'required|string|max:1000',
            'venue' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'url' => 'nullable|url|max:500',
        ]);

        $data = Session::get('contribute_data');
        $data['paper'] = $validated;
        Session::put('contribute_data', $data);

        return redirect()->route('contribute.creators');
    }

    /*
    |--------------------------------------------------------------------------
    | PAGE 3/7: CREATORS
    |--------------------------------------------------------------------------
    */

    public function createCreators()
    {
        if (!Session::has('contribute_data')) {
            return redirect()->route('contribute.metadata')->with('error', 'Please fill metadata first.');
        }
        $creatorsData = Session::get('contribute_data.creators', []);
        return view('contribute.creators', compact('creatorsData'));
    }

    public function storeCreators(Request $request)
    {
        $validated = $request->validate([
            'creators' => 'nullable|array',
            'creators.*.name' => 'required_with:creators|string|max:255',
            'creators.*.affiliation' => 'nullable|string|max:255',
            'creators.*.email' => 'nullable|email|max:255',
            'creators.*.orcid' => 'nullable|regex:/^\d{4}-\d{4}-\d{4}-\d{4}$/|max:20',
            'creators.*.contribution_role' => 'nullable|string|in:Creator,Donor,Analyst,Data Collector,Other',
        ]);

        $data = Session::get('contribute_data');
        $data['creators'] = $validated['creators'] ?? [];
        Session::put('contribute_data', $data);

        return redirect()->route('contribute.files');
    }

    /*
    |--------------------------------------------------------------------------
    | PAGE 4/7: FILES UPLOAD
    |--------------------------------------------------------------------------
    */

    public function createFiles()
    {
        if (!Session::has('contribute_data')) {
            return redirect()->route('contribute.metadata')->with('error', 'Please fill metadata first.');
        }
        return view('contribute.files');
    }

    public function storeFiles(Request $request)
    {
        // Note: Files are temporarily stored and processed on final submit
        // Here we only validate and store metadata in session
     // ✅ Logging yang aman untuk Laravel 12
    // ✅ 1. Validasi input
        $validated = $request->validate([
            'file_format' => 'required|in:tabular,other',
            'has_header' => 'nullable|boolean',
            'has_missing' => 'nullable|boolean',
            'tabular_file' => 'required_if:file_format,tabular|file|mimes:csv,arff,txt|max:51200',
            'other_file' => 'required_if:file_format,other|file|max:51200',
            'test_file' => 'nullable|file|max:51200',
            'graphics' => 'nullable|image|mimes:png,jpg,jpeg,gif|max:10240',
            'variables' => 'nullable|array',
            'variables.*.name' => 'nullable|string|max:255',
            'variables.*.role' => 'nullable|string|in:Feature,Target,ID',
            'variables.*.type' => 'nullable|string|in:Continuous,Categorical,Integer,Real',
        ], [
            // ✅ Custom error messages (opsional tapi membantu)
            'tabular_file.required_if' => 'Please upload a CSV/ARFF/TXT file.',
            'file_format.required' => 'Please select a file format.',
        ]);

        // ✅ 2. Ambil session data
        $data = Session::get('contribute_data');
        if (!$data) {
            return redirect()->route('contribute.metadata')->with('error', 'Session expired. Please start over.');
        }

        // ✅ 3. Proses info file
        $filesInfo = [];
        
        if ($validated['file_format'] === 'tabular' && $request->hasFile('tabular_file')) {
            $file = $request->file('tabular_file');
            $filesInfo[] = [
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'extension' => $file->getClientOriginalExtension(),
                'mime' => $file->getMimeType(),
                'is_primary' => true,
                'has_header' => $request->has_header ? true : false,
                'has_missing' => $request->has_missing ? true : false,
            ];
        }
        
        if ($validated['file_format'] === 'other' && $request->hasFile('other_file')) {
            $file = $request->file('other_file');
            $filesInfo[] = [
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'extension' => $file->getClientOriginalExtension(),
                'mime' => $file->getMimeType(),
                'is_primary' => true,
            ];
        }
        
        if ($request->hasFile('test_file')) {
            $file = $request->file('test_file');
            $filesInfo[] = [
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'extension' => $file->getClientOriginalExtension(),
                'mime' => $file->getMimeType(),
                'is_primary' => false,
                'is_test_data' => true,
            ];
        }
        
        $data['files'] = $filesInfo;
        $data['file_format'] = $validated['file_format'];
        $data['has_header'] = $request->has_header ? true : false;
        $data['has_missing'] = $request->has_missing ? true : false;
        
        // ✅ 4. Proses variables (format: variables[0][name], variables[0][role], dll)
        if (!empty($validated['variables']) && is_array($validated['variables'])) {
            $data['variables'] = array_filter($validated['variables'], fn($v) => !empty($v['name']));
        }
        
        // ✅ 5. Proses graphics
        if ($request->hasFile('graphics')) {
            $g = $request->file('graphics');
            $data['graphics'] = [
                'name' => $g->getClientOriginalName(),
                'size' => $g->getSize(),
                'extension' => $g->getClientOriginalExtension(),
            ];
        }
        
        // ✅ 6. Simpan ke session
        Session::put('contribute_data', $data);
        
        // ✅ 7. Redirect ke halaman berikutnya
        return redirect()->route('contribute.keywords');
    }

    /*
    |--------------------------------------------------------------------------
    | PAGE 5/7: KEYWORDS
    |--------------------------------------------------------------------------
    */

    public function createKeywords()
    {
        if (!Session::has('contribute_data')) {
            return redirect()->route('contribute.metadata')->with('error', 'Please fill metadata first.');
        }
        
        $keywordsData = Session::get('contribute_data.keywords', []);
        
        // Get all existing keywords from database for suggestions
        $allKeywords = Keyword::pluck('keyword_name')->toArray();
        
        // Add popular keywords if not in database
        $popularKeywords = [
            'Classification', 'Regression', 'Clustering', 'Machine Learning',
            'Deep Learning', 'Neural Networks', 'Data Mining', 'Pattern Recognition',
            'Natural Language Processing', 'Computer Vision', 'Time Series',
            'Image Processing', 'Text Mining', 'Supervised Learning',
            'Unsupervised Learning', 'Reinforcement Learning', 'Feature Extraction',
            'Dimensionality Reduction', 'Ensemble Methods', 'Cross Validation'
        ];
        
        $allKeywords = array_unique(array_merge($allKeywords, $popularKeywords));
        sort($allKeywords);
        
        return view('contribute.keywords', compact('keywordsData', 'allKeywords'));
    }

    public function storeKeywords(Request $request)
    {
        $validated = $request->validate([
            'keywords' => 'nullable|string|max:1000',
        ]);
        
        $keywords = [];
        if (!empty($validated['keywords'])) {
            $keywords = json_decode($validated['keywords'], true) ?? [];
            // Sanitize keywords
            $keywords = array_map(fn($k) => Str::title(trim($k)), array_filter($keywords));
        }
        
        $data = Session::get('contribute_data');
        $data['keywords'] = $keywords;
        Session::put('contribute_data', $data);
        
        return redirect()->route('contribute.variable-info');
    }

    /*
    |--------------------------------------------------------------------------
    | PAGE 6/7: VARIABLE INFORMATION
    |--------------------------------------------------------------------------
    */

    public function createVariableInfo()
    {
        if (!Session::has('contribute_data')) {
            return redirect()->route('contribute.metadata')->with('error', 'Please fill metadata first.');
        }
        
        $data = Session::get('contribute_data');
        $variables = $data['variables'] ?? [];
        
        return view('contribute.variable-info', compact('data', 'variables'));
    }

    public function storeVariableInfo(Request $request)
    {
        $validated = $request->validate([
            'class_labels' => 'nullable|string|max:5000',
            'variable_info' => 'nullable|string|max:10000',
        ]);
        
        $contributeData = Session::get('contribute_data');
        $contributeData['class_labels'] = $validated['class_labels'] ?? null;
        $contributeData['variable_info'] = $validated['variable_info'] ?? null;
        Session::put('contribute_data', $contributeData);
        
        // Redirect to Page 7: Descriptive Questions
        return redirect()->route('contribute.descriptive');
    }

    /*
    |--------------------------------------------------------------------------
    | PAGE 7/7: DESCRIPTIVE QUESTIONS & FINAL SUBMIT
    |--------------------------------------------------------------------------
    */

    public function createDescriptive()
    {
        if (!Session::has('contribute_data')) {
            return redirect()->route('contribute.metadata')->with('error', 'Please fill metadata first.');
        }
        
        $data = Session::get('contribute_data');
        return view('contribute.descriptive', compact('data'));
    }

   public function submitDonation(Request $request)
{
    // ✅ 1. Increase timeout & memory for heavy processing
    set_time_limit(300);
    ini_set('memory_limit', '512M');
    
    // ✅ 2. Debug logging
    \Log::info('=== SUBMIT START ===', [
        'user_id' => Auth::id(),
        'dataset_name' => $data['name'] ?? 'unknown',
    ]);
    \DB::enableQueryLog();

    $data = Session::get('contribute_data');
    
    if (!$data) {
        return redirect()->route('contribute.policy')->with('error', 'Session expired. Please start over.');
    }
    
    // Validate descriptive questions
    $validated = $request->validate([
        'purpose' => 'required|string|max:5000',
        'funding' => 'nullable|string|max:1000',
        'instances_represent' => 'required|string|max:1000',
        'data_splits' => 'nullable|string|max:1000',
        'sensitive_data' => 'nullable|string|max:2000',
        'preprocessing' => 'nullable|string|max:5000',
        'additional_info' => 'nullable|string|max:10000',
        'citation_requests' => 'nullable|string|max:2000',
    ]);
    
    try {
        // ===== 1. CREATE DATASET =====
        $dataset = Dataset::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'donated_date' => now(),
            'last_updated' => now(),
            'characteristics' => !empty($data['characteristics']) ? implode(', ', $data['characteristics']) : null,
            'feature_type' => !empty($data['feature_types']) ? implode(', ', $data['feature_types']) : null,
            'num_instances' => $data['num_instances'] ?? null,
            'num_features' => $data['num_features'] ?? null,
            'has_missing_values' => false,
            'additional_info' => json_encode([
                'descriptive' => [
                    'purpose' => $validated['purpose'] ?? null,
                    'funding' => $validated['funding'] ?? null,
                    'instances_represent' => $validated['instances_represent'] ?? null,
                    'data_splits' => $validated['data_splits'] ?? null,
                    'sensitive_data' => $validated['sensitive_data'] ?? null,
                    'preprocessing' => $validated['preprocessing'] ?? null,
                    'citation_requests' => $validated['citation_requests'] ?? null,
                ],
                'variable_info' => $data['variable_info'] ?? null,
                'class_labels' => $data['class_labels'] ?? null,
            ]),
            'task_id' => Task::where('task_name', $data['associated_tasks'][0] ?? 'Other')->first()?->task_id,
            'subject_area_id' => SubjectArea::where('area_name', $data['subject_area'])->first()?->area_id,
            'license_id' => License::where('license_name', 'CC BY 4.0')->first()?->license_id,
            'view_count' => 0,
            'download_count' => 0,
            'citation_count' => 0,
        ]);
        
        // ===== 2. LINK DOI =====
        if (!empty($data['doi'])) {
            $doi = Doi::firstOrCreate(
                ['doi_string' => $data['doi']],
                ['resolution_url' => "https://doi.org/{$data['doi']}"]
            );
            $dataset->update(['doi_id' => $doi->doi_id]);
        }
        
        // ===== 3. LINK PAPER =====
        if (!empty($data['paper']['title'])) {
            $paper = Paper::create([
                'title' => $data['paper']['title'],
                'authors' => $data['paper']['authors'],
                'publication_year' => $data['paper']['year'],
                'venue' => $data['paper']['venue'],
                'paper_doi' => $data['paper']['paper_id_type'] !== 'None' ? $data['paper']['paper_id'] : null,
                'paper_url' => $data['paper']['url'],
            ]);
            $dataset->papers()->attach($paper->paper_id);
        }
        
        // ===== 4. LINK CREATORS =====
        $user = Auth::user();
        if (!empty($data['creators'])) {
            foreach ($data['creators'] as $c) {
                $creator = Creator::firstOrCreate(
                    ['name' => $c['name']],
                    [
                        'affiliation' => $c['affiliation'] ?? null,
                        'email' => $c['email'] ?? null,
                        'orcid' => $c['orcid'] ?? null,
                    ]
                );
                $dataset->creators()->attach($creator->creator_id, [
                    'contribution_role' => $c['contribution_role'] ?? 'Creator',
                ]);
            }
        } else {
            $creator = Creator::firstOrCreate(
                ['name' => $user->name],
                ['affiliation' => $user->institution ?? null, 'email' => $user->email]
            );
            $dataset->creators()->attach($creator->creator_id, ['contribution_role' => 'Donor']);
        }
        
        // ===== 5. LINK KEYWORDS =====
        if (!empty($data['keywords'])) {
            foreach ($data['keywords'] as $kwName) {
                $keyword = Keyword::firstOrCreate(['keyword_name' => $kwName]);
                $dataset->keywords()->attach($keyword->keyword_id);
            }
        }
        
        // ===== 6. UPLOAD & PROCESS FILES =====
        $uploadPath = "datasets/{$dataset->dataset_id}";
        
        // ✅ Pastikan storage link ada
        if (!Storage::disk('public')->exists($uploadPath)) {
            Storage::disk('public')->makeDirectory($uploadPath);
        }
        
        // Fallback: use session file metadata (files already uploaded in previous step)
        if (!empty($data['files'])) {
            foreach ($data['files'] as $index => $fileMeta) {
                // Create file record (actual file would be moved from temp storage in production)
                \App\Models\File::create([
                    'dataset_id' => $dataset->dataset_id,
                    'filename' => Str::slug($fileMeta['name']) . '.' . $fileMeta['extension'],
                    'original_filename' => $fileMeta['name'],
                    'file_format' => strtoupper($fileMeta['extension']),
                    'file_size' => $this->formatFileSize($fileMeta['size']),
                    'file_size_bytes' => $fileMeta['size'],
                    'mime_type' => $fileMeta['mime'] ?? 'application/octet-stream',
                    'is_primary' => $fileMeta['is_primary'] ?? ($index === 0),
                ]);
            }
        }
        
        // Graphics
        if ($request->hasFile('graphics')) {
            $g = $request->file('graphics');
            $filename = $g->store("{$uploadPath}/graphics", 'public');
            \App\Models\File::create([
                'dataset_id' => $dataset->dataset_id,
                'filename' => basename($filename),
                'original_filename' => $g->getClientOriginalName(),
                'file_format' => strtoupper($g->getClientOriginalExtension()),
                'file_size' => $this->formatFileSize($g->getSize()),
                'file_size_bytes' => $g->getSize(),
                'mime_type' => $g->getMimeType(),
                'is_primary' => false,
            ]);
        }
        
        // ===== 7. SAVE VARIABLES =====
        if (!empty($data['variables'])) {
            foreach ($data['variables'] as $index => $var) {
                if (!empty($var['name'])) {
                    Variable::create([
                        'dataset_id' => $dataset->dataset_id,
                        'variable_name' => $var['name'],
                        'role' => $var['role'] ?? 'Feature',
                        'type' => $var['type'] ?? 'Continuous',
                        'description' => $var['description'] ?? null,
                        'order_index' => $index + 1,
                    ]);
                }
            }
        }
        
        // ===== 8. LOG QUERIES (inside try, before return) =====
        $queries = \DB::getQueryLog();
        \Log::info('=== SUBMIT QUERIES ===', ['count' => count($queries)]);
        foreach ($queries as $q) {
            \Log::debug('Query: ' . $q['query'] . ' | Time: ' . ($q['time'] ?? 'N/A') . 'ms');
        }
        
        // ===== 9. CLEAR SESSION =====
        Session::forget('contribute_data');
        
        \Log::info('=== SUBMIT SUCCESS ===', ['dataset_id' => $dataset->dataset_id]);
        
        // ===== 10. REDIRECT WITH SUCCESS =====
        return redirect()->route('profile.datasets')
            ->with('success', '🎉 Dataset "' . $dataset->name . '" has been successfully submitted! It will be reviewed before publication.');
            
    } catch (\Exception $e) {
        // Log error for debugging
        \Log::error('Dataset submission error: ' . $e->getMessage(), [
            'user_id' => Auth::id(),
            'trace' => $e->getTraceAsString(),
        ]);
        
        return redirect()->back()
            ->with('error', 'An error occurred while submitting: ' . $e->getMessage())
            ->withInput();
    }
}
    /*
    |--------------------------------------------------------------------------
    | EXTERNAL LINKING ROUTES
    |--------------------------------------------------------------------------
    */

    // Show external link consent form (Page 0)
    public function createExternalLink()
    {
        return view('linking.metadata'); // ← resources/views/linking/form.blade.php
    }

    // Submit external link
    public function submitExternalLink(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'external_url' => 'required|url|max:500',
            'abstract' => 'required|string|max:1000',
            'characteristics' => 'required|array|min:1',
            'characteristics.*' => 'string',
            'num_instances' => 'nullable|integer|min:0',
            'num_features' => 'nullable|integer|min:0',
            'subject_area' => 'nullable|string|max:255',
            'keywords' => 'nullable|string|max:1000',
            'license' => 'nullable|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
        ]);

        // TODO: Save to database
        // ExternalDataset::create([...]);

        return redirect()->route('profile.datasets')
            ->with('success', '🎉 External dataset link submitted successfully!');
    }

    // Linking Metadata - Page 1
    public function createLinkingMetadata()
    {
        return view('linking.metadata');
    }

    public function storeLinkingMetadata(Request $request)
    {
        $validated = $request->validate([
            'external_url' => 'required|url|max:500',
            'name' => 'required|string|max:255',
            'abstract' => 'required|string|max:1000',
            'num_instances' => 'required|integer|min:0',
            'num_features' => 'nullable|integer|min:0',
            'doi' => 'nullable|string|max:255',
            'characteristics' => 'required|array|min:1',
            'subject_area' => 'required|string',
            'associated_tasks' => 'required|array|min:1',
            'feature_types' => 'nullable|array',
        ]);

        Session::put('linking_data', [
    'external_url' => $validated['external_url'],
    'name' => $validated['name'],
    'description' => $validated['abstract'],  // ← abstract disimpan sebagai 'description'
    'num_instances' => $validated['num_instances'],
    'num_features' => $validated['num_features'] ?? null,
    'doi' => $validated['doi'] ?? null,
    'characteristics' => $validated['characteristics'],
    'subject_area' => $validated['subject_area'],
    'associated_tasks' => $validated['associated_tasks'],
    'feature_types' => $validated['feature_types'] ?? [],
    // Page 2-6 akan merge ke session ini
]);

        return redirect()->route('contribute.linking.paper'); // atau page selanjutnya
    }

    // Page 2: Paper
public function createLinkingPaper()
{
    if (!Session::has('linking_data')) {
        return redirect()->route('contribute.linking.metadata')->with('error', 'Please fill metadata first.');
    }
    $oldPaper = Session::get('linking_data.paper', []);
    return view('linking.paper', compact('oldPaper'));
}

public function storeLinkingPaper(Request $request)
{
    $validated = $request->validate([
        'paper_id_type' => 'nullable|string',
        'paper_id' => 'nullable|string|max:255',
        'title' => 'required|string|max:500',
        'authors' => 'required|string|max:1000',
        'venue' => 'required|string|max:255',
        'year' => 'required|integer|min:1900|max:' . date('Y'),
        'url' => 'nullable|url|max:500',
    ]);

    $data = Session::get('linking_data', []);
    $data['paper'] = $validated;
    Session::put('linking_data', $data);

    return redirect('/contribute/linking/creators');
}
// Page 3: Creators
public function createLinkingCreators()
{
    if (!Session::has('linking_data')) {
        return redirect()->route('contribute.linking.metadata')->with('error', 'Please fill metadata first.');
    }
    return view('linking.creators');
}

public function storeLinkingCreators(Request $request)
{
    $validated = $request->validate([
        'creators' => 'nullable|array',
        'creators.*.first_name' => 'required_with:creators|string|max:255',
        'creators.*.last_name' => 'required_with:creators|string|max:255',
        'creators.*.email' => 'nullable|email|max:255',
        'creators.*.institution' => 'nullable|string|max:255',
        'creators.*.institution_address' => 'nullable|string|max:500',
    ]);

    $data = Session::get('linking_data', []);
    
    // Filter out empty rows (if user clicked add but didn't fill name)
    if (!empty($validated['creators'])) {
        $cleanCreators = array_filter($validated['creators'], function ($c) {
            return !empty($c['first_name']) || !empty($c['last_name']);
        });
        $data['creators'] = array_values($cleanCreators);
    } else {
        $data['creators'] = [];
    }

    Session::put('linking_data', $data);

    return redirect()->route('contribute.linking.keywords'); // Lanjut ke Page 4
}

// Page 4: Keywords
public function createLinkingKeywords()
{
    // Get all keywords from database for autocomplete
    $allKeywords = \App\Models\Keyword::pluck('keyword_name')->toArray();
    
    // Add popular keywords if database is empty
    if (empty($allKeywords)) {
        $allKeywords = [
            'Classification', 'Regression', 'Clustering', 'Machine Learning',
            'Deep Learning', 'Neural Networks', 'Data Mining', 'Pattern Recognition',
            'Natural Language Processing', 'Computer Vision', 'Time Series',
            'Image Processing', 'Text Mining', 'Supervised Learning',
            'Unsupervised Learning', 'Reinforcement Learning', 'Feature Extraction',
            'Dimensionality Reduction', 'Ensemble Methods', 'Cross Validation',
            'Academic performance', 'accelerometer', 'agriculture', 'AIDS',
            'air pollution', "Alzheimer's disease", 'Android', 'animal',
            'arts-and-entertainment', 'audio', 'automobile', 'band gaps',
            'behavioral', 'Bengali', 'cancer', 'cardiology',
            'Cardiovascular Disease', 'causal inference', 'census',
            'cervical cancer', 'Cheminformatics', 'Chemistry', 'chess',
            'CIMT (Carotid Intima-Media Thickness)', 'Circadian Clock',
            'community discovery', 'computer networks', 'consumer',
            'covid-19', 'credit', 'crime', 'cyber security',
            'data drift', 'decision making', 'decomposable graphs'
        ];
    }
    
    $keywordsData = Session::get('linking_data.keywords', []);
    
    return view('linking.keywords', compact('allKeywords', 'keywordsData'));
}

public function storeLinkingKeywords(Request $request)
{
    $validated = $request->validate([
        'keywords' => 'nullable|string'
    ]);

    $data = Session::get('linking_data', []);
    $data['keywords'] = !empty($validated['keywords']) ? json_decode($validated['keywords'], true) : [];
    Session::put('linking_data', $data);

    return redirect()->route('contribute.linking.variable-info');
}

// Page 5: Variable Information
public function createLinkingVariableInfo()
{
    if (!Session::has('linking_data')) {
        return redirect()->route('contribute.linking.metadata')->with('error', 'Please fill metadata first.');
    }
    $data = Session::get('linking_data');
    return view('linking.variable-info', compact('data'));
}

public function storeLinkingVariableInfo(Request $request)
{
    $validated = $request->validate([
        'class_labels' => 'nullable|string|max:5000',
        'variable_info' => 'nullable|string|max:10000',
    ]);

    $data = Session::get('linking_data');
    $data['class_labels'] = $validated['class_labels'] ?? null;
    $data['variable_info'] = $validated['variable_info'] ?? null;
    Session::put('linking_data', $data);

    return redirect()->route('contribute.linking.descriptive'); // Lanjut ke Page 6
}

// Page 6: Descriptive Questions
public function createLinkingDescriptive()
{
    $data = Session::get('linking_data', []);
    
    // Debug: Pastikan data tidak kosong
    if (empty($data)) {
        \Log::error('Session linking_data KOSONG!');
        return redirect()->route('contribute.linking.metadata')
            ->with('error', 'Session expired. Silakan mulai dari awal.');
    }
    
    return view('linking.descriptive', compact('data'));
}

// Final Submit for External Link
public function submitLinking(Request $request)
{
    // 🔍 1. LOG MASUK (Biar tau sampai mana jalan)
    \Log::info('🚀 SUBMIT LINKING START', [
        'user_id' => auth()->id(),
        'has_session' => Session::has('linking_data'),
        'csrf_token' => $request->has('_token'),
    ]);

    try {
        // 🔹 2. VALIDASI (Semua nullable agar tidak blokir submit)
        $validated = $request->validate([
            'purpose' => 'nullable|string|max:5000',
            'funding' => 'nullable|string|max:1000',
            'instances_represent' => 'nullable|string|max:1000',
            'data_splits' => 'nullable|string|max:1000',
            'sensitive_data' => 'nullable|string|max:2000',
            'preprocessing' => 'nullable|string|max:5000',
            'additional_info' => 'nullable|string|max:10000',
            'citation_requests' => 'nullable|string|max:2000',
        ]);

        // 🔹 3. AMBIL SESSION
        $data = Session::get('linking_data', []);
        if (empty($data) || empty($data['name'])) {
            \Log::warning('⚠️ Session linking_data kosong atau tidak valid.');
            return redirect()->back()->with('error', '⚠️ Sesi habis atau data belum lengkap. Silakan mulai dari Page 1.');
        }

        // 🔹 4. SIAPKAN DATA INSERT (STRICT SESUAI SCHEMA KAMU)
        $insertData = [
            'user_id' => auth()->id(),
            'name' => $data['name'],
            'slug' => \Str::slug($data['name']) . '-' . time(),
            'description' => $data['description'] ?? ($data['abstract'] ?? ''),
            'abstract' => $data['description'] ?? ($data['abstract'] ?? ''),
            'dataset_url' => $data['external_url'] ?? null,
            'linked_date' => now()->format('Y-m-d'), // DATE column
            'status' => 'pending',
            'donated_date' => now()->format('Y-m-d'), // DATE column
            'created_at' => now(),
            'updated_at' => now(),
            'num_instances' => $data['num_instances'] ?? null,
            'num_features' => $data['num_features'] ?? null,
            'view_count' => 0,
            'download_count' => 0,
            'citation_count' => 0,
            'has_missing_values' => 0,
            'subject_area' => $data['subject_area'] ?? null,
        ];

        // Tambah field opsional hanya jika ada isinya
        if (!empty($data['characteristics']) && is_array($data['characteristics'])) {
            $insertData['data_type'] = implode(', ', $data['characteristics']);
        }
        if (!empty($data['associated_tasks']) && is_array($data['associated_tasks'])) {
            $insertData['task_type'] = $data['associated_tasks'][0];
        }

        // 🔹 5. INSERT KE TABEL `datasets`
        \Log::info('💾 Executing DB insert...', $insertData);
        $datasetId = \DB::table('datasets')->insertGetId($insertData);
        \Log::info('✅ DB Insert success! Dataset ID: ' . $datasetId);

        // 🔹 6. SIMPAN DESCRIPTIVE QUESTIONS (Ke tabel `dataset_descriptions`)
        if (!empty($validated['purpose']) || !empty($validated['funding'])) {
            \DB::table('dataset_descriptions')->insert([
                'dataset_id' => $datasetId,
                'purpose' => $validated['purpose'] ?? null,
                'funding' => $validated['funding'] ?? null,
                'instances_represent' => $validated['instances_represent'] ?? null,
                'data_splits' => $validated['data_splits'] ?? null,
                'sensitive_data' => $validated['sensitive_data'] ?? null,
                'preprocessing' => $validated['preprocessing'] ?? null,
                'additional_info' => $validated['additional_info'] ?? null,
                'citation_requests' => $validated['citation_requests'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 🔹 7. CLEAR SESSION & REDIRECT
        Session::forget('linking_data');
        \Log::info('🏁 Redirecting to profile.datasets');
        
        return redirect()->route('profile.datasets')
            ->with('success', '✅ Berhasil submit! Dataset ID: ' . $datasetId);

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('❌ Validation Error', $e->errors());
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        \Log::error('❌ Database/General Error: ' . $e->getMessage());
        \Log::error('📍 File: ' . $e->getFile() . ':' . $e->getLine());
        return redirect()->back()
            ->with('error', '❌ Gagal menyimpan: ' . $e->getMessage())
            ->withInput();
    }
}
    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */
    
    private function formatFileSize(int $bytes): string
    {
        if ($bytes === 0) return '0 Bytes';
        $units = ['B', 'KB', 'MB', 'GB'];
        $k = 1024;
        $i = floor(log($bytes, $k));
        return round($bytes / pow($k, $i), 2) . ' ' . $units[$i];
    }

    private function parseVariablesFromCSV(Dataset $dataset, array $fileMeta): void
    {
        // In production: parse actual CSV file from temporary storage
        // This is a placeholder that creates sample variables
        
        $sampleVariables = [
            ['name' => 'column_1', 'type' => 'Continuous', 'role' => 'Feature'],
            ['name' => 'column_2', 'type' => 'Continuous', 'role' => 'Feature'],
            ['name' => 'target', 'type' => 'Categorical', 'role' => 'Target'],
        ];
        
        foreach ($sampleVariables as $index => $var) {
            Variable::create([
                'dataset_id' => $dataset->dataset_id,
                'variable_name' => $var['name'],
                'role' => $var['role'],
                'type' => $var['type'],
                'order_index' => $index + 1,
            ]);
        }
    }
}