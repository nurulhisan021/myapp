<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // อ่านจาก config (ปลอดภัยต่อการ cache)
        $emailCfg = (string) config('admin.email');
        $passCfg  = (string) config('admin.password');

        // เทียบแบบตัดช่องว่างและไม่แคร์พิมพ์เล็กใหญ่สำหรับอีเมล
        $emailOk = strcasecmp(trim($data['email']), trim($emailCfg)) === 0;
        $passOk  = hash_equals((string) $data['password'], $passCfg);

        if ($emailOk && $passOk) {
            session(['is_admin' => true, 'admin_email' => $emailCfg]);
            return redirect()->intended(route('admin.dashboard'))->with('ok', 'ยินดีต้อนรับค่ะ');
        }

        return back()->withInput()->with('error', 'อีเมลหรือรหัสผ่านไม่ถูกต้อง');
    }

    public function logout()
    {
        session()->forget(['is_admin', 'admin_email']);
        return redirect()->route('admin.login')->with('ok', 'ออกจากระบบแล้ว');
    }
}
