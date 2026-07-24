<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak. Fitur ini hanya untuk Admin.'
                ], 403);
            }
            abort(403, 'Akses Ditolak: Halaman/Fitur ini hanya dapat diakses oleh Admin.');
        }

        return $next($request);
    }
}
