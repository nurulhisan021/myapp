@extends('layouts.app')
@section('title','ตะกร้าสินค้า')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<h1 class="text-2xl font-bold mb-6">ตะกร้าสินค้า</h1>

@if(empty($cart))
  <div class="rounded-xl border bg-white p-6 text-center text-gray-500">
    <p>ตะกร้าของคุณยังว่างเปล่า</p>
    <a href="{{ route('products.index') }}" class="mt-4 inline-block px-5 py-2 rounded-lg bg-brand text-white hover:bg-brand-dark">เลือกซื้อสินค้า</a>
    </div>
@else
  <div class="rounded-2xl border bg-white overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left min-w-[600px]">
      <thead class="bg-gray-50 text-gray-600 text-sm">
        <tr>
          <th class="p-4">สินค้า</th>
          <th class="p-4 w-32">ราคา</th>
          <th class="p-4 w-40">จำนวน</th>
          <th class="p-4 w-32 text-right">รวม</th>
          <th class="p-4 w-24"></th>
        </tr>
      </thead>
      <tbody class="divide-y">
        @foreach($products as $product)
          @php
            $qty = $cart[$product->id]['qty'];
            $lineTotal = $qty * $product->price;
          @endphp
          <tr class="align-middle">
            <td class="p-4">
              <div class="flex items-center gap-4">
                <a href="{{ route('products.show', $product) }}">
                    <img src="{{ $product->image_url }}" class="w-16 h-16 rounded object-cover border">
                </a>
                <div>
                    <a href="{{ route('products.show', $product) }}" class="font-medium hover:text-brand">{{ $product->name }}</a>
                    <p class="text-sm text-gray-500">{{ $product->category->name ?? '' }}</p>
                    <p class="text-sm text-gray-500">สต็อกคงเหลือ: {{ $product->stock }}</p>
                    @if ($product->stock < $qty)
                        <p class="text-sm text-red-500 font-semibold">! สต็อกไม่เพียงพอ (กรุณาลดจำนวนสินค้า)</p>
                    @endif
                </div>
              </div>
            </td>
            <td class="p-4">฿{{ number_format($product->price, 2) }}</td>
            <td class="p-4">
              <form action="{{ route('cart.update', $product->id) }}" method="POST">
                @csrf
                <input type="number" name="qty" value="{{ $qty }}" min="0" max="{{ $product->stock }}" 
                       onchange="this.form.submit()"
                       class="w-24 rounded-lg border-gray-300 text-center focus:ring-brand focus:border-brand">
              </form>
            </td>
            <td class="p-4 font-semibold text-right">฿{{ number_format($lineTotal, 2) }}</td>
            <td class="p-4 text-center">
              <form action="{{ route('cart.remove', $product->id) }}" method="POST" onsubmit="return confirm('นำสินค้านี้ออกจากตะกร้า?')">
                @csrf @method('DELETE')
                <button class="text-red-500 hover:text-red-700 font-bold text-xl">×</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
    <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('คุณต้องการล้างตะกร้าสินค้าทั้งหมดใช่หรือไม่?')">
      @csrf @method('DELETE')
      <button class="px-4 py-2 rounded-lg border hover:bg-gray-100 text-sm text-gray-600">ล้างตะกร้าทั้งหมด</button>
    </form>
    <div class="flex items-center gap-4">
      <div class="text-right">
        <div class="text-gray-600">ยอดรวมทั้งสิ้น</div>
        <div class="text-2xl font-bold text-brand">฿{{ number_format($total, 2) }}</div>
      </div>
      <a href="{{ route('checkout.index') }}" class="px-6 py-3 rounded-xl bg-brand text-white font-bold hover:bg-brand-dark">
        สั่งซื้อและชำระเงิน
      </a>
    </div>
  </div>
@endif
</div>
@endsection
