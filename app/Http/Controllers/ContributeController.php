<?php

namespace App\Http\Controllers;

use App\Models\{Dataset, Person, Variable, File, Task, SubjectArea, License, Doi, Keyword, Paper};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContributeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PUBLIC ROUTES
    |--------------------------------------------------------------------------
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

        Session::put('contribute_data', array_merge([
            'paper' => [], 'creators' => [], 'files' => [], 
            'keywords' => [], 'variables' => [], 'variable_info' => null, 'class_labels' => null,
        ], [
            'name' => $validated['name'],
            'description' => $validated['abstract'],
            'num_instances' => $validated['num_instances'],
            'num_features' => $validated['num_features'] ?? null,
            'doi' => $validated['doi'] ?? null,
            'characteristics' => $validated['characteristics'],
            'subject_area' => $validated['subject_area'],
            'associated_tasks' => $validated['associated_tasks'],
            'feature_types' => $validated['feature_types'] ?? [],
        ]));

        return redirect()->route('contribute.paper');
    }

    /*
    |--------------------------------------------------------------------------
    | PAGE 2/7: PAPER
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

        $data = Session::get('contribute_data', []);
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

        $data = Session::get('contribute_data', []);
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
        $request->validate(['files' => 'required|array']);
        
        $files = [];
        foreach ($request->file('files') as $i => $f) {
            $tempName = 'tmp_' . uniqid() . '_' . Str::slug($f->getClientOriginalName());
            $path = $f->storeAs('temp/donation', $tempName, 'local');
            
            $files[] = [
                'name' => $f->getClientOriginalName(),
                'extension' => $f->getClientOriginalExtension(),
                'size' => $f->getSize(),
                'mime' => $f->getMimeType(),
                'temp_path' => $path,
                'is_primary' => $i === 0,
            ];
        }
        
        $data = session('contribute_data', []);
        $data['files'] = $files;
        session(['contribute_data' => $data]);
        session()->save();
        
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
        $allKeywords = array_unique(array_merge(
            Keyword::pluck('keyword_name')->toArray(),
            ['Classification', 'Regression', 'Clustering', 'Machine Learning']
        ));
        sort($allKeywords);
        
        return view('contribute.keywords', compact('keywordsData', 'allKeywords'));
    }

    public function storeKeywords(Request $request)
    {
        $validated = $request->validate(['keywords' => 'nullable|string|max:1000']);
        
        $keywords = [];
        if (!empty($validated['keywords'])) {
            $keywords = array_map(fn($k) => Str::title(trim($k)), 
                array_filter(json_decode($validated['keywords'], true) ?? []));
        }
        
        $data = Session::get('contribute_data', []);
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
        $variables = Session::get('contribute_data.variables', []);
        return view('contribute.variable-info', compact('variables'));
    }

    public function storeVariableInfo(Request $request)
    {
        $data = session('contribute_data', []);
        $data['variables'] = $request->input('variables', []);
        session(['contribute_data' => $data]);
        session()->save();
        
        return redirect()->route('contribute.descriptive');
    }

    /*
    |--------------------------------------------------------------------------
    | PAGE 7/7: DESCRIPTIVE & SUBMIT
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
        set_time_limit(300);
        ini_set('memory_limit', '512M');
        
        $data = Session::get('contribute_data');
        
        if (!$data || empty($data['name'])) {
            return redirect()->route('contribute.policy')
                ->with('error', 'Session expired. Please start over.');
        }

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
            DB::beginTransaction();
            Log::info('=== SUBMIT: Start ===', ['user_id' => Auth::id(), 'dataset' => $data['name']]);

            // ===== 1. LOOKUP FOREIGN KEYS DENGAN FALLBACK =====
            $task = Task::where('task_name', $data['associated_tasks'][0] ?? 'Other')->first();
            if (!$task) $task = Task::firstOrCreate(['task_name' => 'Other']);
            
            $subjectArea = SubjectArea::where('area_name', $data['subject_area'] ?? 'Other')->first();
            if (!$subjectArea) $subjectArea = SubjectArea::firstOrCreate(['area_name' => $data['subject_area'] ?? 'Other']);
            
            $license = License::where('license_name', 'CC BY 4.0')->first();
            if (!$license) $license = License::firstOrCreate(['license_name' => 'CC BY 4.0']);

            // ===== 2. CREATE DATASET =====
            $dataset = Dataset::create([
                'user_id' => Auth::id(),
                'name' => $data['name'],
                'description' => $data['description'] ?? '',
                'abstract' => $data['description'] ?? '',
                'donated_date' => now()->format('Y-m-d'),
                'last_updated' => now(),
                'characteristics' => !empty($data['characteristics']) ? implode(', ', $data['characteristics']) : null,
                'feature_type' => !empty($data['feature_types']) ? implode(', ', $data['feature_types']) : null,
                'num_instances' => $data['num_instances'] ?? null,
                'num_features' => $data['num_features'] ?? null,
                'has_missing_values' => false,
                'additional_info' => json_encode([
                    'descriptive' => $validated,
                    'variable_info' => $data['variable_info'] ?? null,
                    'class_labels' => $data['class_labels'] ?? null,
                ]),
                'task_id' => $task->task_id,
                'subject_area_id' => $subjectArea->area_id,
                'license_id' => $license->license_id,
                'view_count' => 0,
                'download_count' => 0,
                'citation_count' => 0,
                'status' => 'pending',
                'slug' => Str::slug($data['name']) . '-' . time(),
            ]);
            Log::info('Dataset created', ['id' => $dataset->dataset_id]);

            // ===== 3. DOI =====
            if (!empty($data['doi'])) {
                $doi = Doi::firstOrCreate(
                    ['doi_string' => $data['doi']],
                    ['resolution_url' => "https://doi.org/{$data['doi']}"]
                );
                $dataset->update(['doi_id' => $doi->doi_id]);
            }

            // ===== 4. PAPER =====
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

            // ===== 5. CONTRIBUTORS =====
            $user = Auth::user();
            $creators = $data['creators'] ?? [];
            
            if (is_array($creators) && count($creators) > 0) {
                foreach ($creators as $i => $c) {
                    if (empty($c['name'])) continue;
                    $person = Person::firstOrCreate(
                        ['name' => $c['name']],
                        [
                            'affiliation' => $c['affiliation'] ?? null,
                            'email' => $c['email'] ?? null,
                            'orcid' => $c['orcid'] ?? null,
                        ]
                    );
                    $dataset->contributors()->attach($person->person_id, [
                        'contribution_role' => $c['contribution_role'] ?? 'Creator',
                        'display_order' => $i + 1,
                    ]);
                }
            } else {
                $person = Person::firstOrCreate(
                    ['name' => $user->name],
                    ['affiliation' => $user->affiliation ?? null, 'email' => $user->email]
                );
                $dataset->contributors()->attach($person->person_id, [
                    'contribution_role' => 'Donor',
                    'display_order' => 1,
                ]);
            }

            // ===== 6. KEYWORDS ✅ FIX: Tambahkan slug =====
            if (!empty($data['keywords']) && is_array($data['keywords'])) {
                foreach ($data['keywords'] as $kw) {
                    if (empty($kw)) continue;
                    $keyword = Keyword::firstOrCreate(
                        ['keyword_name' => $kw],
                        ['slug' => Str::slug($kw)] // ✅ FIX UTAMA
                    );
                    $dataset->keywords()->attach($keyword->keyword_id);
                }
            }

            // ===== 7. VARIABLES =====
            if (!empty($data['variables']) && is_array($data['variables'])) {
                foreach ($data['variables'] as $i => $var) {
                    if (!empty($var['name'])) {
                        Variable::create([
                            'dataset_id' => $dataset->dataset_id,
                            'variable_name' => $var['name'],
                            'role' => $var['role'] ?? 'Feature',
                            'type' => $var['type'] ?? 'Continuous',
                            'description' => $var['description'] ?? null,
                            'order_index' => $i + 1,
                            'is_visible' => true,
                        ]);
                    }
                }
            }

           // ===== 8. FILES =====
if (!empty($data['files']) && is_array($data['files'])) {
    $uploadPath = "datasets/{$dataset->dataset_id}";
    Storage::disk('public')->makeDirectory($uploadPath);

    foreach ($data['files'] as $i => $fileMeta) {
        if (!isset($fileMeta['temp_path'])) continue;
        
        if (Storage::disk('local')->exists($fileMeta['temp_path'])) {
            $content = Storage::disk('local')->get($fileMeta['temp_path']);
            $finalName = basename($fileMeta['temp_path']);
            $finalPath = "{$uploadPath}/{$finalName}";
            
            Storage::disk('public')->put($finalPath, $content);

            File::create([
                'dataset_id' => $dataset->dataset_id,
                'filename' => $finalName,
                'original_filename' => $fileMeta['name'],
                'file_format' => strtoupper($fileMeta['extension']),
                'file_size' => $this->formatFileSize($fileMeta['size']),
                'file_size_bytes' => $fileMeta['size'],
                'mime_type' => $fileMeta['mime'],
                'is_primary' => $fileMeta['is_primary'] ?? ($i === 0),
                'file_role' => 'data',
                'file_path' => $finalPath, // ✅ FIX: Ganti storage_path jadi file_path
            ]);
            
            Storage::disk('local')->delete($fileMeta['temp_path']);
        }
    }
}

            DB::commit();
            Session::forget('contribute_data');
            
            if (Storage::disk('local')->directoryExists('temp/donation')) {
                Storage::disk('local')->deleteDirectory('temp/donation');
            }
            
            Log::info('=== SUBMIT: SUCCESS ===', ['dataset_id' => $dataset->dataset_id]);
            
            return redirect()->route('profile.datasets')
                ->with('success', '🎉 Dataset "' . $dataset->name . '" berhasil disubmit! Menunggu review.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('SUBMIT FAILED: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'file' => $e->getFile() . ':' . $e->getLine(),
            ]);
            
            return redirect()->back()
                ->with('error', 'Gagal submit: ' . $e->getMessage())
                ->withInput();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */
    
    protected function formatFileSize($bytes)
    {
        if (!is_numeric($bytes) || $bytes <= 0) return '0 B';
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }

    // ===== EXTERNAL LINKING ROUTES (Tetap sama) =====
    public function createExternalLink() { return view('linking.metadata'); }
    
    public function submitExternalLink(Request $request) {
        return redirect()->route('profile.datasets')->with('success', 'External link submitted!');
    }
    
    public function createLinkingMetadata() { 
        return view('linking.metadata'); }
    
    public function storeLinkingMetadata(Request $request) {
        $validated = $request->validate([
            'external_url' => 'required|url|max:500', 'name' => 'required|string|max:255',
            'abstract' => 'required|string|max:1000', 'num_instances' => 'required|integer|min:0',
            'num_features' => 'nullable|integer|min:0', 'doi' => 'nullable|string|max:255',
            'characteristics' => 'required|array|min:1', 'subject_area' => 'required|string',
            'associated_tasks' => 'required|array|min:1', 'feature_types' => 'nullable|array',
        ]);
        Session::put('linking_data', array_merge([
            'paper' => [], 'creators' => [], 'keywords' => [], 'variable_info' => null, 'class_labels' => null,
        ], [
            'external_url' => $validated['external_url'], 'name' => $validated['name'],
            'description' => $validated['abstract'], 'num_instances' => $validated['num_instances'],
            'num_features' => $validated['num_features'] ?? null, 'doi' => $validated['doi'] ?? null,
            'characteristics' => $validated['characteristics'], 'subject_area' => $validated['subject_area'],
            'associated_tasks' => $validated['associated_tasks'], 'feature_types' => $validated['feature_types'] ?? [],
        ]));
        return redirect()->route('contribute.linking.paper');
    }
    
    public function createLinkingPaper() {
        if (!Session::has('linking_data')) return redirect()->route('contribute.linking.metadata')->with('error', 'Fill metadata first.');
        $oldPaper = Session::get('linking_data.paper', []);
        return view('linking.paper', compact('oldPaper'));
    }
    
    public function storeLinkingPaper(Request $request) {
        $validated = $request->validate([
            'paper_id_type' => 'nullable|string', 'paper_id' => 'nullable|string|max:255',
            'title' => 'required|string|max:500', 'authors' => 'required|string|max:1000',
            'venue' => 'required|string|max:255', 'year' => 'required|integer|min:1900|max:' . date('Y'),
            'url' => 'nullable|url|max:500',
        ]);
        $data = Session::get('linking_data', []);
        $data['paper'] = $validated;
        Session::put('linking_data', $data);
        return redirect('/contribute/linking/creators');
    }
    
    public function createLinkingCreators() {
        if (!Session::has('linking_data')) return redirect()->route('contribute.linking.metadata')->with('error', 'Fill metadata first.');
        return view('linking.creators');
    }
    
    public function storeLinkingCreators(Request $request) {
        $validated = $request->validate([
            'creators' => 'nullable|array',
            'creators.*.first_name' => 'required_with:creators|string|max:255',
            'creators.*.last_name' => 'required_with:creators|string|max:255',
            'creators.*.email' => 'nullable|email|max:255',
            'creators.*.institution' => 'nullable|string|max:255',
            'creators.*.institution_address' => 'nullable|string|max:500',
        ]);
        $data = Session::get('linking_data', []);
        if (!empty($validated['creators'])) {
            $cleanCreators = array_filter($validated['creators'], fn($c) => !empty($c['first_name']) || !empty($c['last_name']));
            $data['creators'] = array_values($cleanCreators);
        } else { $data['creators'] = []; }
        Session::put('linking_data', $data);
        return redirect()->route('contribute.linking.keywords');
    }
    
    public function createLinkingKeywords() {
        $allKeywords = array_unique(array_merge(
            Keyword::pluck('keyword_name')->toArray(),
            ['Classification', 'Regression', 'Clustering', 'Machine Learning']
        ));
        $keywordsData = Session::get('linking_data.keywords', []);
        return view('linking.keywords', compact('allKeywords', 'keywordsData'));
    }
    
    public function storeLinkingKeywords(Request $request) {
        $validated = $request->validate(['keywords' => 'nullable|string']);
        $data = Session::get('linking_data', []);
        $data['keywords'] = !empty($validated['keywords']) ? json_decode($validated['keywords'], true) : [];
        Session::put('linking_data', $data);
        return redirect()->route('contribute.linking.variable-info');
    }
    
    public function createLinkingVariableInfo() {
        if (!Session::has('linking_data')) return redirect()->route('contribute.linking.metadata')->with('error', 'Fill metadata first.');
        $data = Session::get('linking_data');
        return view('linking.variable-info', compact('data'));
    }
    
    public function storeLinkingVariableInfo(Request $request) {
        $validated = $request->validate([
            'class_labels' => 'nullable|string|max:5000',
            'variable_info' => 'nullable|string|max:10000',
        ]);
        $data = Session::get('linking_data');
        $data['class_labels'] = $validated['class_labels'] ?? null;
        $data['variable_info'] = $validated['variable_info'] ?? null;
        Session::put('linking_data', $data);
        return redirect()->route('contribute.linking.descriptive');
    }
    
    public function createLinkingDescriptive() {
        $data = Session::get('linking_data', []);
        if (empty($data)) {
            Log::error('Session linking_data KOSONG!');
            return redirect()->route('contribute.linking.metadata')->with('error', 'Session expired.');
        }
        return view('linking.descriptive', compact('data'));
    }
    
    public function submitLinking(Request $request) {
        Log::info('🚀 SUBMIT LINKING START', ['user_id' => auth()->id()]);
        try {
            $validated = $request->validate([
                'purpose' => 'nullable|string|max:5000', 'funding' => 'nullable|string|max:1000',
                'instances_represent' => 'nullable|string|max:1000', 'data_splits' => 'nullable|string|max:1000',
                'sensitive_data' => 'nullable|string|max:2000', 'preprocessing' => 'nullable|string|max:5000',
                'additional_info' => 'nullable|string|max:10000', 'citation_requests' => 'nullable|string|max:2000',
            ]);
            $data = Session::get('linking_data', []);
            if (empty($data) || empty($data['name'])) {
                Log::warning('⚠️ Session linking_data kosong');
                return redirect()->back()->with('error', '⚠️ Sesi habis. Silakan mulai dari awal.');
            }
            $insertData = [
                'user_id' => auth()->id(), 'name' => $data['name'],
                'slug' => Str::slug($data['name']) . '-' . time(),
                'description' => $data['description'] ?? ($data['abstract'] ?? ''),
                'abstract' => $data['description'] ?? ($data['abstract'] ?? ''),
                'dataset_url' => $data['external_url'] ?? null,
                'linked_date' => now()->format('Y-m-d'), 'status' => 'pending',
                'donated_date' => now()->format('Y-m-d'), 'created_at' => now(), 'updated_at' => now(),
                'num_instances' => $data['num_instances'] ?? null, 'num_features' => $data['num_features'] ?? null,
                'view_count' => 0, 'download_count' => 0, 'citation_count' => 0, 'has_missing_values' => 0,
                'subject_area' => $data['subject_area'] ?? null,
            ];
            if (!empty($data['characteristics']) && is_array($data['characteristics'])) {
                $insertData['data_type'] = implode(', ', $data['characteristics']);
            }
            if (!empty($data['associated_tasks']) && is_array($data['associated_tasks'])) {
                $insertData['task_type'] = $data['associated_tasks'][0];
            }
            Log::info('💾 Executing DB insert...');
            $datasetId = DB::table('datasets')->insertGetId($insertData);
            Log::info('✅ DB Insert success! Dataset ID: ' . $datasetId);
            if (!empty($validated['purpose']) || !empty($validated['funding'])) {
                DB::table('dataset_descriptions')->insert([
                    'dataset_id' => $datasetId,
                    'purpose' => $validated['purpose'] ?? null, 'funding' => $validated['funding'] ?? null,
                    'instances_represent' => $validated['instances_represent'] ?? null,
                    'data_splits' => $validated['data_splits'] ?? null,
                    'sensitive_data' => $validated['sensitive_data'] ?? null,
                    'preprocessing' => $validated['preprocessing'] ?? null,
                    'additional_info' => $validated['additional_info'] ?? null,
                    'citation_requests' => $validated['citation_requests'] ?? null,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }
            Session::forget('linking_data');
            Log::info('🏁 Redirecting');
            return redirect()->route('profile.datasets')->with('success', '✅ Berhasil submit! Dataset ID: ' . $datasetId);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('❌ Validation Error', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('❌ Database/General Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', '❌ Gagal: ' . $e->getMessage())->withInput();
        }
    }
}