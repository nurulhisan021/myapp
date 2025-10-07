@extends('layouts.app')
@section('title','Admin Dashboard')

@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="text-xl font-semibold">แดชบอร์ดผู้ดูแล</h1>
  <form method="POST" action="{{ route('admin.logout') }}">
    @csrf
    <button class="px-3 py-2 rounded-lg border hover:bg-gray-50">ออกจากระบบ</button>
  </form>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
  <div class="bg-white border rounded-2xl p-4">
    <div class="text-sm text-gray-500">จำนวนสินค้า</div>
    <div class="text-3xl font-semibold">{{ $stats['products'] }}</div>
  </div>
</div>

<div class="mt-6 flex gap-2">
  <a href="{{ route('products.create') }}" class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">+ เพิ่มสินค้า</a>
  <a href="{{ route('products.index') }}" class="px-4 py-2 rounded-xl border hover:bg-gray-50">ไปดูสินค้าทั้งหมด</a>
</div>
@endsection
