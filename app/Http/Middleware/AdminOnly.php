<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('is_admin')) {
            return redirect()->route('admin.login')->with('error', 'โปรดล็อกอินผู้ดูแลก่อน');
        }
        return $next($request);
    }
}
