<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::guard('staff')->user();

        if (!$user) {
            return redirect()->route('staff.login');
        }

        if (!in_array($user->role, $roles)) {
            abort(403);
        }

        return $next($request);
    }
}