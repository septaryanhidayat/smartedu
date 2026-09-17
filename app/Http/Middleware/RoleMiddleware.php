<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = auth()->user();

        // Super Admin has access to all roles
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        if (!empty($roles) && !$user->hasRole($roles)) {
            abort(403, '⛔ Akses Ditolak: Peran akun Anda (' . ($user->role ?? 'User') . ') tidak memiliki izin untuk membuka modul ini.');
        }

        return $next($request);
    }
}
