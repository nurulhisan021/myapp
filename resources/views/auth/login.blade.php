@extends('layouts.app')
@section('title','เข้าสู่ระบบ')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
  <div class="w-full max-w-md">
    <div class="bg-white border rounded-2xl p-8 shadow-sm">
      <h1 class="text-2xl font-bold mb-2 text-center">เข้าสู่ระบบ</h1>
      <p class="text-gray-500 text-center mb-6">ยินดีต้อนรับกลับมา</p>

      @if ($errors->any())
        <div class="mb-4 rounded-md bg-red-50 border border-red-200 text-red-700 p-3 text-sm">
          <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('login.submit') }}" class="space-y-4">
        @csrf
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">อีเมล</label>
          <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand focus:border-brand @error('email') border-red-500 @enderror">
          @error('email')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">รหัสผ่าน</label>
          <input type="password" name="password" id="password" required
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand focus:border-brand">
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-gray-600">
                <input type="checkbox" name="remember" value="1" class="rounded border-gray-300 text-brand shadow-sm focus:ring-brand">
                <span>จดจำฉันไว้</span>
            </label>
            {{-- <a href="#" class="text-sm text-brand hover:underline">ลืมรหัสผ่าน?</a> --}}
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full px-4 py-3 font-semibold rounded-lg bg-brand text-white hover:bg-brand-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand">
                เข้าสู่ระบบ
            </button>
        </div>
      </form>
    </div>

    <p class="text-center text-sm text-gray-600 mt-6">
        ยังไม่มีบัญชี? 
        <a href="{{ route('register') }}" class="font-medium text-brand hover:underline">สมัครสมาชิกที่นี่</a>
    </p>
  </div>
</div>
@endsection
