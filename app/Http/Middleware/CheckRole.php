<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
{
    // 1. Get the current user
    $user = Auth::user();
    // 2. Use the MODEL method we just created to check permissions
    if ( ! $user->hasRole($role) ) {
        abort(403, 'Access Denied');
    }

    return $next($request);
}
}