@extends('layouts.app')
@section('title',$product->name)

@section('content')
<div class="grid md:grid-cols-2 gap-8">
  <div class="bg-white rounded-2xl border overflow-hidden">
    <img src="{{ $product->image_url }}" class="w-full aspect-[4/3] object-cover">
  </div>

  <div>
    <h1 class="text-2xl font-semibold">{{ $product->name }}</h1>
    <p class="mt-2 text-brand text-xl font-bold">{{ number_format($product->price,2) }} บาท</p>
    <p class="mt-4 text-gray-600 whitespace-pre-line">{{ $product->description }}</p>

    <div class="mt-4">
        @if($product->stock > 0)
            <span class="text-sm text-gray-500">มีสินค้า <span class="font-semibold">{{ $product->stock }}</span> ชิ้น</span>
        @else
            <span class="text-sm font-semibold text-red-500">สินค้าหมด</span>
        @endif
    </div>

    @auth
        @if(auth()->user()->is_admin)
            {{-- Admin Buttons --}}
            <div class="mt-6 flex items-center gap-2">
                <a href="{{ route('admin.products.edit', $product) }}" class="px-4 py-2 rounded-lg border hover:bg-gray-50">แก้ไข</a>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('ยืนยันลบ?')">
                    @csrf @method('DELETE')
                    <button class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">ลบ</button>
                </form>
            </div>
        @else
            {{-- Logged-in User "Add to Cart" Form --}}
            @if($product->stock > 0)
                <form action="{{ route('cart.add') }}" method="POST" class="mt-6 flex items-center gap-3">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="number" name="qty" value="1" min="1" max="{{ $product->stock }}" class="w-20 rounded-lg border-gray-300 text-center focus:ring-brand focus:border-brand">
                    <button class="px-5 py-2 rounded-xl bg-brand text-white hover:bg-brand-dark">🛒 ใส่ตะกร้า</button>
                </form>
            @else
                <button class="mt-6 w-full px-5 py-2 rounded-xl bg-gray-300 text-gray-500 cursor-not-allowed" disabled>สินค้าหมด</button>
            @endif
        @endif
    @else
        {{-- Guest "Login" Button --}}
        <a href="{{ route('login') }}" class="mt-6 inline-block w-full text-center px-5 py-3 rounded-xl bg-gray-200 text-gray-700 hover:bg-gray-300">
            เข้าสู่ระบบเพื่อสั่งซื้อ
        </a>
    @endauth
  </div>
</div>
@endsection
