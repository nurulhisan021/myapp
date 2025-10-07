@extends('layouts.app')
@section('title','ตะกร้าสินค้า')

@section('content')
<h1 class="text-xl font-semibold mb-4">ตะกร้าสินค้า</h1>

@if(session('error'))
  <div class="mb-3 rounded bg-red-50 border border-red-200 text-red-700 px-3 py-2">{{ session('error') }}</div>
@endif
@if(session('ok'))
  <div class="mb-3 rounded bg-green-50 border border-green-200 text-green-800 px-3 py-2">{{ session('ok') }}</div>
@endif

@php
  // ป้องกันกรณี Controller ไม่ได้ส่งตัวแปรมา
  $items = $items ?? [];
  $subtotal = $subtotal ?? collect($items)->sum('line');
@endphp

@if(empty($items))
  <div class="bg-white border rounded-xl p-6 text-gray-500">
    ยังไม่มีสินค้าในตะกร้า
    <a href="{{ route('products.index') }}" class="text-blue-600 underline ml-1">ไปเลือกซื้อ</a>
  </div>
@else
  <div class="overflow-hidden rounded-2xl border bg-white">
    <table class="w-full text-left">
      <thead class="bg-gray-50 text-gray-600 text-sm">
        <tr>
          <th class="px-4 py-3">สินค้า</th>
          <th class="px-4 py-3">ราคา</th>
          <th class="px-4 py-3">จำนวน</th>
          <th class="px-4 py-3">รวม</th>
          <th class="px-4 py-3 w-32">จัดการ</th>
        </tr>
      </thead>
      <tbody class="divide-y">
      @foreach($items as $it)
        <tr class="hover:bg-gray-50">
          <td class="px-4 py-3">
            <div class="flex items-center gap-3">
              <img
                src="{{ $it['image'] ? asset('storage/'.$it['image']) : asset('images/product-placeholder.png') }}"
                class="w-14 h-14 rounded object-cover border" alt="">
              <span class="font-medium">{{ $it['name'] }}</span>
            </div>
          </td>
          <td class="px-4 py-3">{{ number_format($it['price'], 2) }}</td>
          <td class="px-4 py-3">
            <form action="{{ route('cart.update', $it['id']) }}" method="POST" class="inline-flex items-center gap-2">
              @csrf
              <input type="number" name="qty" min="0" value="{{ $it['qty'] }}"
                     class="w-20 rounded-lg border-gray-300 text-center">
              <button class="px-3 py-1 rounded-lg border hover:bg-gray-100">อัปเดต</button>
            </form>
          </td>
          <td class="px-4 py-3">{{ number_format($it['line'], 2) }}</td>
          <td class="px-4 py-3">
            <form action="{{ route('cart.remove',$it['id']) }}" method="POST"
                  onsubmit="return confirm('ลบรายการนี้?')" class="inline-block">
              @csrf @method('DELETE')
              <button class="px-3 py-1 rounded-lg bg-red-600 text-white hover:bg-red-700">ลบ</button>
            </form>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>

  <div class="mt-4 flex items-center justify-between">
    <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('ล้างตะกร้าทั้งหมด?')">
      @csrf @method('DELETE')
      <button class="px-4 py-2 rounded-xl border hover:bg-gray-50">ล้างตะกร้า</button>
    </form>

    <div class="text-right">
      <div class="text-sm text-gray-500">ยอดรวม</div>
      <div class="text-2xl font-semibold text-pink-600">{{ number_format($subtotal,2) }} บาท</div>
      <div class="mt-3 flex gap-2 justify-end">
        <a href="{{ route('products.index') }}" class="px-4 py-2 rounded-xl border hover:bg-gray-50">เลือกซื้อเพิ่ม</a>
        <button class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700" disabled>
          ชำระเงิน (Demo)
        </button>
      </div>
    </div>
  </div>
@endif
@endsection
