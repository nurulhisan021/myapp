@extends('layouts.app')
@section('title', 'แก้ไขโปรไฟล์')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">แก้ไขโปรไฟล์</h1>

        <div class="bg-white p-8 rounded-2xl border shadow-sm">
            {{-- Success Message --}}
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('account.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Name --}}
                <div class="mb-4">
                    <label for="name" class="block font-medium mb-1">ชื่อ</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required 
                           class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-brand @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="block font-medium mb-1">อีเมล</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-brand @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <hr class="my-6">

                <p class="font-medium mb-2">เปลี่ยนรหัสผ่าน (กรอกหากต้องการเปลี่ยน)</p>

                {{-- Password --}}
                <div class="mb-4">
                    <label for="password" class="block font-medium mb-1">รหัสผ่านใหม่</label>
                    <input type="password" id="password" name="password"
                           class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-brand @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password Confirmation --}}
                <div class="mb-6">
                    <label for="password_confirmation" class="block font-medium mb-1">ยืนยันรหัสผ่านใหม่</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-brand">
                </div>

                {{-- Submit Button --}}
                <div>
                    <button type="submit" class="px-6 py-3 rounded-xl bg-brand text-white font-bold hover:bg-brand-dark w-full sm:w-auto">
                        บันทึกการเปลี่ยนแปลง
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
