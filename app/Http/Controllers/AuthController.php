<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;   // ใช้ระบบ Auth ของ Laravel
use Illuminate\Support\Facades\Hash;   // สำหรับเข้ารหัสรหัสผ่าน
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed', // ต้องมี password_confirmation
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => strtolower(trim($data['email'])),
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user); // ล็อกอินทันทีหลังสมัคร
        return redirect()->intended(route('account.home'))->with('ok', 'สมัครสมาชิกและเข้าสู่ระบบเรียบร้อยค่ะ');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = (bool) $request->input('remember', false);

        // แปลง email ให้เป็น lower-case ก่อนเทียบ
        $credentials['email'] = strtolower(trim($credentials['email']));

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate(); // ปลอดภัยขึ้น
            return redirect()->intended(route('account.home'))->with('ok','ยินดีต้อนรับค่ะ');
        }

        return back()->withInput()->with('error','อีเมลหรือรหัสผ่านไม่ถูกต้อง');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('ok','ออกจากระบบแล้ว');
    }
}
