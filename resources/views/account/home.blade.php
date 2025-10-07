@extends('layouts.app')
@section('title','บัญชีของฉัน')

@section('content')
<div class="max-w-2xl mx-auto bg-white border rounded-2xl p-6">
  <h1 class="text-xl font-semibold">บัญชีของฉัน</h1>
  <p class="mt-2">สวัสดี, <b>{{ auth()->user()->name }}</b> ({{ auth()->user()->email }})</p>

  <div class="mt-4 flex items-center gap-2">
    <a href="{{ route('products.index') }}" class="px-4 py-2 rounded-xl border hover:bg-gray-50">ไปเลือกซื้อสินค้า</a>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button class="px-4 py-2 rounded-xl bg-gray-800 text-white hover:bg-gray-900">ออกจากระบบ</button>
    </form>
  </div>
</div>
@endsection
