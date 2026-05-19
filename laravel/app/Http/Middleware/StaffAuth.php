<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StaffAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('staff_logged_in')) {
            return redirect()->route('staff.login');
        }

        return $next($request);
    }
}