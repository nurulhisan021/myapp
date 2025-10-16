<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $admins = User::where('is_admin', true)->latest()->paginate(10);
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        if (!auth()->user()->is_super_admin) {
            return back()->with('error', 'คุณไม่มีสิทธิ์สร้างแอดมินใหม่');
        }

        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->is_super_admin) {
            return back()->with('error', 'คุณไม่มีสิทธิ์สร้างแอดมินใหม่');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => true,
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'สร้างแอดมินใหม่สำเร็จ');
    }

    public function edit(User $admin)
    {
        if (!auth()->user()->is_super_admin && $admin->id !== auth()->id()) {
            return back()->with('error', 'คุณไม่มีสิทธิ์แก้ไขแอดมินคนอื่น');
        }

        return view('admin.admins.edit', compact('admin'));
    }

    public function update(Request $request, User $admin)
    {
        if (!auth()->user()->is_super_admin && $admin->id !== auth()->id()) {
            return back()->with('error', 'คุณไม่มีสิทธิ์แก้ไขแอดมินคนอื่น');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $admin->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $admin->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.admins.index')->with('success', 'อัปเดตข้อมูลแอดมินสำเร็จ');
    }

    public function destroy(User $admin)
    {
        if (!auth()->user()->is_super_admin) {
            return back()->with('error', 'คุณไม่มีสิทธิ์ลบแอดมิน');
        }

        // Prevent deleting the last admin
        if (User::where('is_admin', true)->count() <= 1) {
            return back()->with('error', 'ไม่สามารถลบแอดมินคนสุดท้ายได้');
        }

        // Prevent self-deletion
        if ($admin->id === auth()->id()) {
            return back()->with('error', 'ไม่สามารถลบตัวเองได้');
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')->with('success', 'ลบแอดมินสำเร็จ');
    }
}
