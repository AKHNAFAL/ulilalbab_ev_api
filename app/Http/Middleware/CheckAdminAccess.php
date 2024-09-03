<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdminAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->role && $user->role->access_level === 'admin') {
            return $next($request);
        }

        return response()->json(['message' => 'Role Unauthorized'], 403);
    }
}
