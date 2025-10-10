<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('is_admin')) {
            return redirect()->route('admin.login')
                ->with('error', 'กรุณาเข้าสู่ระบบแอดมิน');
        }
        if (!Auth::user()->is_admin) {
            return redirect()->route('shop.home')->with('error', 'ต้องเป็นแอดมินเท่านั้น');
        }
        return $next($request);
    }
}
