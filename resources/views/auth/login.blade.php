@extends('layouts.app')
@section('title','เข้าสู่ระบบ')

@section('content')
<div class="min-h-[60vh] grid place-items-center">
  <div class="w-full max-w-sm bg-white border rounded-2xl p-6 shadow-sm">
    <h1 class="text-xl font-semibold mb-4">เข้าสู่ระบบ</h1>

    @if ($errors->any())
      <div class="mb-3 rounded bg-red-50 border border-red-200 text-red-700 px-3 py-2">
        {{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}" class="space-y-4">
      @csrf
      <div>
        <label class="block text-sm mb-1">อีเมล</label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus
               class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-brand">
      </div>
      <div>
        <label class="block text-sm mb-1">รหัสผ่าน</label>
        <input type="password" name="password" required
               class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-brand">
      </div>
      <label class="inline-flex items-center gap-2 text-sm">
        <input type="checkbox" name="remember" value="1"> จดจำฉันไว้
      </label>
      <button class="w-full px-4 py-2 rounded-xl bg-brand text-white hover:bg-brand-dark">เข้าสู่ระบบ</button>
    </form>

    <div class="text-center text-xs text-gray-500 mt-4">
      แอดมินล็อกอินด้วย: {{ env('ADMIN_EMAIL') }} / {{ env('ADMIN_PASSWORD') }}
    </div>

    <div class="mt-4 text-center text-sm">
      ยังไม่มีบัญชี? <a class="text-brand hover:underline" href="{{ route('register') }}">สมัครสมาชิก</a>
    </div>
  </div>
</div>
@endsection
