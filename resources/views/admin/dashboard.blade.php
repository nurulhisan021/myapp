@extends('admin.layout')
@section('title','แดชบอร์ดแอดมิน')

@section('admin_content')
<div class="max-w-5xl mx-auto">
  <h1 class="text-2xl font-bold mb-6">แดชบอร์ดแอดมิน</h1>

  @if(session('ok'))
    <div class="mb-4 rounded bg-green-50 text-green-800 px-3 py-2">{{ session('ok') }}</div>
  @endif

  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="rounded-xl border bg-white p-5">
      <div class="text-sm text-gray-500">จำนวนสินค้า</div>
      <div class="text-3xl font-semibold mt-1">{{ $stats['products'] }}</div>
    </div>
    <div class="rounded-xl border bg-white p-5">
      <div class="text-sm text-gray-500">สถานะ</div>
      <div class="mt-1">พร้อมใช้งาน ✅</div>
    </div>
    <div class="rounded-xl border bg-white p-5">
      <div class="text-sm text-gray-500">เมนูลัด</div>
      <div class="mt-2 space-x-2">
        <a href="{{ route('admin.products.create') }}" class="px-3 py-2 rounded bg-blue-600 text-white">+ เพิ่มสินค้า</a>
        <a href="{{ route('products.index') }}" class="px-3 py-2 rounded border">หน้าสินค้า (ลูกค้า)</a>
      </div>
    </div>
  </div>
</div>
@endsection
