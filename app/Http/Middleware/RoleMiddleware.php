<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (!$user || !in_array($user->role, $roles)) {
            if ($request->expectsJson()) {
                abort(403, 'Akses ditolak.');
            }
            return redirect($user && $user->isStaff() ? '/admin/layar-tv' : '/katalog');
        }

        return $next($request);
    }
}
