@extends('layouts.app')
@section('title','สมัครสมาชิก')

@section('content')
<div class="min-h-[60vh] grid place-items-center">
  <div class="w-full max-w-sm bg-white border rounded-2xl p-6 shadow-sm">
    <h1 class="text-xl font-semibold mb-4">สมัครสมาชิก</h1>

    @if ($errors->any())
      <div class="mb-3 rounded bg-red-50 border border-red-200 text-red-700 px-3 py-2">
        {{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="{{ route('register.submit') }}" class="space-y-4">
      @csrf
      <div>
        <label class="block text-sm mb-1">ชื่อ</label>
        <input type="text" name="name" value="{{ old('name') }}" required
               class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-brand">
      </div>
      <div>
        <label class="block text-sm mb-1">อีเมล</label>
        <input type="email" name="email" value="{{ old('email') }}" required
               class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-brand">
      </div>
      <div>
        <label class="block text-sm mb-1">รหัสผ่าน</label>
        <input type="password" name="password" required class="w-full rounded-lg border-gray-300">
      </div>
      <div>
        <label class="block text-sm mb-1">ยืนยันรหัสผ่าน</label>
        <input type="password" name="password_confirmation" required class="w-full rounded-lg border-gray-300">
      </div>
      <button class="w-full px-4 py-2 rounded-xl bg-brand text-white hover:bg-brand-dark">สมัครสมาชิก</button>
    </form>
  </div>
</div>
@endsection
