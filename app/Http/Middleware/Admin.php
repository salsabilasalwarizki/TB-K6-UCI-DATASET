<?php

namespace App\Http\Middleware;  // ✅ Namespace benar

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Admin  // ✅ Nama class: Admin (sesuai nama file)
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }
        
        return redirect()->route('home')->with('error', 'Akses ditolak.');
    }
}