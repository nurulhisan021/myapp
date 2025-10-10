<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (session('is_admin')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $cred = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // อ่านจาก config ถ้ามี; ถ้าไม่มี fallback ไป env (กันเคสยังไม่เพิ่มใน config/app.php)
        $okEmail = (string) (config('app.admin_email') ?? env('ADMIN_EMAIL'));
        $okPass  = (string) (config('app.admin_password') ?? env('ADMIN_PASSWORD'));

        $email = strtolower(trim($cred['email']));
        $pass  = (string) $cred['password'];

        if ($okEmail && $okPass && $email === strtolower($okEmail) && hash_equals($okPass, $pass)) {
            $request->session()->put('is_admin', true);
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'))->with('ok', 'ยินดีต้อนรับ');
        }

        return back()->withErrors(['email' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('is_admin');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('ok', 'ออกจากระบบแล้ว');
    }
}
