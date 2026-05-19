<?php
namespace App\Http\Controllers;

use App\Models\Dataset;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $popularDatasets = Dataset::with(['task', 'subjectArea'])
            ->orderBy('view_count', 'desc')
            ->take(4)
            ->get();
        
        $newDatasets = Dataset::with(['task', 'subjectArea'])
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
        
        $totalDatasets = Dataset::count();
        
        return view('home', compact('popularDatasets', 'newDatasets', 'totalDatasets'));
    }
    
    public function about()
    {
        return view('about');
    }
    // app/Http/Controllers/HomeController.php
public function whoWeAre() { return view('about.who-we-are'); }
public function citation() { return view('about.citation'); }
public function contact() { return view('about.contact'); }
}