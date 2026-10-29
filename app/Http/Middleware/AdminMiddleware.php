<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Periksa apakah pengguna sudah login
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect()->route('admin.login')
                ->with('error_message', 'Silakan masuk terlebih dahulu untuk mengakses area administratif.');
        }

        // 2. Periksa apakah akun memiliki hak akses administrator
        if (!Auth::user()->is_admin) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden. Admin privileges required.'], 403);
            }

            abort(403, 'Akses ditolak. Akun Anda tidak memiliki hak akses administrator.');
        }

        return $next($request);
    }
}
