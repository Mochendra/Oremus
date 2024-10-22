<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $userRole = Auth::user()->role;

            // Cek apakah role user adalah 'admin' atau 'user'
            if ($userRole == 'admin' || $userRole == 'user') {
                return $next($request);
            }
        }

        // Redirect jika tidak memiliki akses
        return redirect('/unauthorized');
    }

}
