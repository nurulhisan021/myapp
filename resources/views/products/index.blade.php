@extends('layouts.app')
@section('title','สินค้า')

@section('content')
@php
  // ถ้าอยู่ในโซน /admin/... จะถือเป็นหน้าแอดมิน
  $isAdmin = request()->is('admin/*');
  $cartCount = collect(session('cart', []))->sum('qty');
@endphp

<div class="flex items-center justify-between mb-4">
  <form method="get" class="flex gap-2">
    <input type="text" name="q" value="{{ $q }}"
           placeholder="ค้นหาชื่อสินค้า"
           class="w-64 rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">
    <button class="px-3 py-2 rounded-lg bg-gray-800 text-white hover:bg-gray-900">ค้นหา</button>
    @if($q)
      <a href="{{ route('products.index') }}" class="px-3 py-2 rounded-lg border hover:bg-gray-50">เคลียร์</a>
    @endif
  </form>

  <div class="flex items-center gap-2">
    @unless($isAdmin)
      {{-- ลูกค้า: ปุ่มไปตะกร้า --}}
      <a href="{{ route('cart.index') }}"
         class="inline-flex items-center px-4 py-2 rounded-xl border hover:bg-gray-50">
         🛒 ตะกร้า @if($cartCount) <span class="ml-1 text-pink-600 font-semibold">({{ $cartCount }})</span> @endif
      </a>
    @else
      {{-- แอดมิน: ปุ่มเพิ่มสินค้า --}}
      <a href="{{ route('products.create') }}"
         class="inline-flex items-center px-4 py-2 rounded-xl bg-blue-600 text-white shadow hover:bg-blue-700">
         + เพิ่มสินค้า
      </a>
    @endunless
  </div>
</div>

@if(session('ok'))
  <div class="mb-3 rounded bg-green-50 border border-green-200 text-green-800 px-3 py-2">
    {{ session('ok') }}
  </div>
@endif
@if(session('error'))
  <div class="mb-3 rounded bg-red-50 border border-red-200 text-red-700 px-3 py-2">
    {{ session('error') }}
  </div>
@endif

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
  @forelse($products as $p)
    <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
      <a href="{{ route('products.show',$p) }}">
        <img src="{{ $p->image_url }}" alt="{{ $p->name }}" class="w-full h-48 object-cover">
      </a>
      <div class="p-4">
        <a href="{{ route('products.show',$p) }}" class="font-medium block truncate">{{ $p->name }}</a>
        <p class="text-pink-600 font-semibold mt-1">{{ number_format($p->price,2) }} บาท</p>
        <p class="text-sm text-gray-500 mt-2 truncate">{{ $p->description }}</p>

        @unless($isAdmin)
          {{-- ลูกค้า: ปุ่มใส่ตะกร้า --}}
          <form action="{{ route('cart.add') }}" method="POST" class="mt-3 flex items-center gap-2">
            @csrf
            <input type="hidden" name="product_id" value="{{ $p->id }}">
            <input type="number" name="qty" value="1" min="1"
                   class="w-16 rounded-lg border-gray-300 text-center">
            <button class="px-3 py-1.5 rounded-lg bg-pink-600 text-white hover:bg-pink-700">
              🛒 ใส่ตะกร้า
            </button>
            <a href="{{ route('cart.index') }}" class="text-sm text-gray-600 underline">ดูตะกร้า</a>
          </form>
        @else
          {{-- แอดมิน: แสดงปุ่มจัดการ --}}
          <div class="flex items-center gap-2 mt-3">
            <a href="{{ route('products.edit',$p) }}"
               class="px-3 py-1 rounded-lg border hover:bg-gray-100">แก้ไข</a>
            <form action="{{ route('products.destroy',$p) }}" method="POST"
                  onsubmit="return confirm('ยืนยันลบสินค้า?')" class="inline-block">
              @csrf @method('DELETE')
              <button class="px-3 py-1 rounded-lg bg-red-600 text-white hover:bg-red-700">ลบ</button>
            </form>
          </div>
        @endunless
      </div>
    </div>
  @empty
    <p class="text-gray-500">ยังไม่มีสินค้า</p>
  @endforelse
</div>

<div class="mt-4">
  {{ $products->links() }}
</div>
@endsection
