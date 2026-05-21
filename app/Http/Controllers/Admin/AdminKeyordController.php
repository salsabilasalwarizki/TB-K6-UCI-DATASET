<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keyword;
use Illuminate\Http\Request;

class AdminKeywordController extends Controller
{
    public function index(Request $request)
    {
        $query = Keyword::withCount('datasets');
        if ($request->filled('search')) $query->where('keyword_name', 'like', "%{$request->search}%");
        $keywords = $query->orderBy('keyword_name')->paginate(20)->withQueryString();
        return view('admin.keywords.index', compact('keywords'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['keyword_name' => 'required|string|max:100|unique:keywords,keyword_name']);
        Keyword::create(array_merge($validated, ['slug' => \Str::slug($validated['keyword_name'])]));
        return back()->with('success', 'Keyword added.');
    }

    public function update(Request $request, Keyword $keyword)
    {
        $validated = $request->validate(['keyword_name' => 'required|string|max:100|unique:keywords,keyword_name,'.$keyword->keyword_id]);
        $keyword->update(array_merge($validated, ['slug' => \Str::slug($validated['keyword_name'])]));
        return back()->with('success', 'Keyword updated.');
    }

    public function destroy(Keyword $keyword)
    {
        $keyword->delete();
        return back()->with('success', 'Keyword deleted.');
    }
}