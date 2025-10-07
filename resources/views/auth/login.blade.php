@extends('layouts.app')
@section('title','เข้าสู่ระบบ')

@section('content')
<div class="max-w-sm mx-auto bg-white border rounded-2xl p-6 shadow-sm">
  <h1 class="text-xl font-semibold mb-4">เข้าสู่ระบบ</h1>

  @if(session('error'))
    <div class="mb-3 rounded bg-red-50 border border-red-200 text-red-700 px-3 py-2">{{ session('error') }}</div>
  @endif
  @if(session('ok'))
    <div class="mb-3 rounded bg-green-50 border border-green-200 text-green-800 px-3 py-2">{{ session('ok') }}</div>
  @endif

  <form method="POST" action="{{ route('login.submit') }}" class="space-y-3">
    @csrf
    <div>
      <label class="block text-sm mb-1">อีเมล</label>
      <input type="email" name="email" value="{{ old('email') }}" required
             class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">
      @error('email')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <div>
      <label class="block text-sm mb-1">รหัสผ่าน</label>
      <input type="password" name="password" required
             class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">
      @error('password')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <label class="inline-flex items-center gap-2">
      <input type="checkbox" name="remember" value="1">
      <span class="text-sm">จำฉันไว้</span>
    </label>
    <button class="w-full px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">เข้าสู่ระบบ</button>
  </form>

  <div class="text-center text-sm mt-4">
    ยังไม่มีบัญชี? <a href="{{ route('register') }}" class="text-blue-600 underline">สมัครสมาชิก</a>
  </div>
</div>
@endsection
