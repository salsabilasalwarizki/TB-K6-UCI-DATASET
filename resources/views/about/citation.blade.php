@extends('layouts.app')
@section('title', 'Citation Metadata - UCI Machine Learning Repository')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <h1 class="text-3xl font-bold text-blue-600 mb-8">Citation Metadata</h1>
        
        <div class="bg-white rounded-xl border border-gray-200 p-8 shadow-sm">
            
            <h2 class="text-xl font-semibold text-blue-600 mb-4">How to Cite Datasets</h2>
            <p class="text-gray-600 leading-relaxed mb-6">
                When using datasets from the UCI Machine Learning Repository in your research, please cite them appropriately 
                to acknowledge the contributors and maintain reproducibility.
            </p>
            
            <h3 class="text-lg font-semibold text-blue-600 mt-8 mb-3">General Citation Format</h3>
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg mb-6">
                <p class="text-gray-700">
                    <strong class="text-blue-700">Author(s)</strong> (Year). 
                    <em>Dataset Name</em> [Dataset]. 
                    UCI Machine Learning Repository. 
                    <span class="text-blue-600">https://doi.org/xxxx</span>
                </p>
            </div>
            
            <h3 class="text-lg font-semibold text-blue-600 mt-8 mb-3">BibTeX Format</h3>
            <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm font-mono leading-relaxed">@dataset{datasetname,
  author = {Author Name},
  title = {Dataset Name},
  year = {2024},
  publisher = {UCI Machine Learning Repository},
  url = {https://archive.ics.uci.edu/dataset/xxx},
  doi = {10.24433/CO.xxxxxx.x}
}</pre>
            
            <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-800">
                    <strong>💡 Tip:</strong> Each dataset page has a "Cite" button that generates the correct citation format automatically.
                </p>
            </div>
        </div>
        
    </div>
</div>
@endsection