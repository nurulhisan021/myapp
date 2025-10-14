<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->is_admin
                ? redirect()->route('admin.dashboard')
                : redirect()->route('account.orders.index');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $cred = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
            'remember' => 'sometimes|boolean',
        ], [
            'email.required' => 'กรุณากรอกอีเมล',
            'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
        ]);

        $remember = (bool)($cred['remember'] ?? false);

        if (Auth::attempt(['email'=>$cred['email'], 'password'=>$cred['password']], $remember)) {
            $request->session()->regenerate();

            if (Auth::user()->is_admin) {
                return redirect()->intended(route('admin.dashboard'))->with('ok','ยินดีต้อนรับแอดมิน');
            }

            // For regular users, check if the intended URL is an admin route.
            $intendedUrl = session('url.intended', route('shop.home'));

            // Check if the path of the intended URL starts with /admin
            if (str_starts_with(parse_url($intendedUrl, PHP_URL_PATH), '/admin')) {
                // If so, redirect to a default safe route for regular users
                return redirect()->route('shop.home')->with('ok', 'เข้าสู่ระบบสำเร็จ');
            }

            // Otherwise, proceed to the intended URL
            return redirect($intendedUrl)->with('ok', 'เข้าสู่ระบบสำเร็จ');
        }
        return back()->withErrors(['email'=>'อีเมลหรือรหัสผ่านไม่ถูกต้อง'])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) return redirect()->route('account.orders.index');
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'กรุณากรอกชื่อ',
            'email.required' => 'กรุณากรอกอีเมล',
            'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.unique' => 'อีเมลนี้ถูกใช้งานแล้ว',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
            'password.min' => 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร',
            'password.confirmed' => 'การยืนยันรหัสผ่านไม่ตรงกัน',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'is_admin' => false, // ลูกค้าปกติ
        ]);

        // No automatic login, redirect to login page with a success message
        return redirect()->route('login')->with('ok', 'สมัครสมาชิกสำเร็จแล้ว! กรุณาเข้าสู่ระบบ');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('shop.home')->with('ok','ออกจากระบบแล้ว');
    }
}
