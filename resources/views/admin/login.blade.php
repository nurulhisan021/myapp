@extends('layouts.app')
@section('title','เข้าสู่ระบบผู้ดูแล')

@section('content')
<div class="min-h-[70vh] grid place-items-center px-4">
  <div class="w-full max-w-sm bg-white border rounded-2xl p-6 shadow-sm">
    <h1 class="text-xl font-semibold mb-4">เข้าสู่ระบบผู้ดูแล</h1>

    @if(session('error'))
      <div class="mb-3 rounded bg-red-50 border border-red-200 text-red-700 px-3 py-2">
        {{ session('error') }}
      </div>
    @endif
    @if(session('ok'))
      <div class="mb-3 rounded bg-green-50 border border-green-200 text-green-800 px-3 py-2">
        {{ session('ok') }}
      </div>
    @endif

    @if ($errors->any())
      <div class="mb-3 rounded bg-red-50 border border-red-200 text-red-700 px-3 py-2">
        {{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
      @csrf

      <div>
        <label class="block text-sm mb-1">อีเมล</label>
        <input type="email"
               name="email"
               value="{{ old('email') }}"
               required
               autofocus
               autocomplete="username"
               inputmode="email"
               class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">
        @error('email')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
      </div>

      <div>
        <label class="block text-sm mb-1">รหัสผ่าน</label>
        <div class="relative">
          <input type="password"
                 id="admin-password"
                 name="password"
                 required
                 autocomplete="current-password"
                 class="w-full rounded-lg border-gray-300 pr-16 focus:ring-2 focus:ring-blue-500">
          <button type="button" id="toggle-pass"
                  class="absolute right-1.5 top-1/2 -translate-y-1/2 px-3 py-1 text-sm rounded-lg border hover:bg-gray-50">
            แสดง
          </button>
        </div>
        @error('password')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
      </div>

      <button class="w-full px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">
        เข้าสู่ระบบ
      </button>
    </form>

    {{-- โชว์ credential เฉพาะตอนพัฒนาเท่านั้น (ไม่ใช้ env() ตรง ๆ) --}}
    @if(app()->environment('local'))
      <div class="text-center text-xs text-gray-500 mt-4">
        ตัวอย่าง (local): {{ config('app.admin_email', env('ADMIN_EMAIL')) }}
        /
        {{ config('app.admin_password', env('ADMIN_PASSWORD')) }}
      </div>
    @endif
  </div>
</div>
@endsection

@push('scripts')
<script>
  (function () {
    const btn = document.getElementById('toggle-pass');
    const input = document.getElementById('admin-password');
    if (!btn || !input) return;
    btn.addEventListener('click', () => {
      input.type = input.type === 'password' ? 'text' : 'password';
      btn.textContent = input.type === 'password' ? 'แสดง' : 'ซ่อน';
    });
  })();
</script>
@endpush
