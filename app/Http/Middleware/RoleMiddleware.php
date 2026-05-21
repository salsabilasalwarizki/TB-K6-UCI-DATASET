<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // 2. Cek apakah user memiliki salah satu role yang diizinkan
        // $roles akan berisi array dari argument middleware, misal: ['admin', 'superadmin']
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // 3. Jika tidak punya akses, tolak (403 Forbidden)
        // Atau bisa ganti dengan redirect()->route('home')->with('error', 'Unauthorized');
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}