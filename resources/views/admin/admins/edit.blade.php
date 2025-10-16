@extends('admin.layout')
@section('title', 'แก้ไขแอดมิน')

@section('admin_content')
<h1 class="text-2xl font-bold mb-6">แก้ไขแอดมิน</h1>

<div class="max-w-lg mx-auto bg-white border rounded-lg shadow-sm p-6">
    <form action="{{ route('admin.admins.update', $admin) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">ชื่อ</label>
            <input type="text" name="name" id="name" value="{{ old('name', $admin->name) }}" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand focus:border-brand @error('name') border-red-500 @enderror">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">อีเมล</label>
            <input type="email" name="email" id="email" value="{{ old('email', $admin->email) }}" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand focus:border-brand @error('email') border-red-500 @enderror">
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">รหัสผ่านใหม่ (เว้นว่างไว้หากไม่ต้องการเปลี่ยน)</label>
            <input type="password" name="password" id="password"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand focus:border-brand @error('password') border-red-500 @enderror">
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">ยืนยันรหัสผ่านใหม่</label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand focus:border-brand">
        </div>

        <div class="flex items-center gap-4 mt-6">
            <button type="submit" class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">
                บันทึก
            </button>
            <a href="{{ route('admin.admins.index') }}" class="text-gray-600 hover:underline">
                ยกเลิก
            </a>
        </div>
    </form>
</div>
@endsection
