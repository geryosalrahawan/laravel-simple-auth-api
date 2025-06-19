<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = auth()->user();
    //         dd([
    //     'user_role' => $user->role,
    //     'role_value' => $user->role instanceof \BackedEnum ? $user->role->value : $user->role,
    //     'expected' => $roles
    // ]);

        if (! $user || ! in_array($user->role->value, $roles)) {
            return response()->json(['error' => 'Forbidden - insufficient permissions'], 403);
        }

        return $next($request);
    }
}