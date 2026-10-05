<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        string $role
    ): Response {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        /** @var \App\Models\Akun|null $user */
        $user = Auth::user();

        if (
            !$user
            || $user->status_akun !== 'AKTIF'
            || $user->tipe_akun !== strtoupper($role)
        ) {
            abort(
                403,
                'Anda tidak memiliki akses ke halaman ini.'
            );
        }

        return $next($request);
    }
}
