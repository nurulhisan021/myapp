@extends('layouts.app')
@section('title','ช็อป')

@section('content')
@php
  $cartCount = collect(session('cart', []))->sum('qty');
@endphp

{{-- ===== HERO ===== --}}
<section class="relative h-96 md:h-[500px] flex items-center justify-center text-center bg-cover bg-center bg-[url('https://images.unsplash.com/photo-1596462502278-27bfdc403348?q=80&w=2670&auto=format&fit=crop')]">
    <div class="absolute inset-0 bg-black/50"></div>
    <div class="relative z-10 px-4 text-white">
        <h1 class="text-4xl md:text-6xl font-extrabold drop-shadow-lg">ช็อปความสวยที่ใช่สำหรับคุณ</h1>
        <p class="mt-4 text-lg md:text-xl text-white/90 max-w-2xl mx-auto">รวมสกินแคร์และเมคอัพแบรนด์ดัง ราคาดีที่สุด จัดส่งไว พร้อมโปรโมชั่นมากมาย</p>
        <div class="mt-8">
            <a href="/products" class="px-8 py-3 rounded-full bg-brand text-white font-semibold shadow-lg hover:bg-brand-dark transform hover:scale-105 transition-transform duration-300">
                เลือกซื้อสินค้าทั้งหมด
            </a>
        </div>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    {{-- ===== CATEGORIES ===== --}}
    @if(isset($categories) && $categories->isNotEmpty())
    <section class="py-12">
        <div class="flex items-center justify-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">เลือกซื้อตามหมวดหมู่</h2>
        </div>
        <div class="overflow-x-auto pb-4">
            <div class="flex items-center justify-start gap-4 border-b whitespace-nowrap">
            <a href="{{ route('shop.home') }}" 
               class="px-4 py-2 font-semibold transition whitespace-nowrap rounded-full {{ !$selectedCategory ? 'bg-brand text-white' : 'text-gray-500 hover:text-brand' }}">
                ทั้งหมด
            </a>
            @foreach ($categories as $category)
                <a href="{{ route('shop.home', ['category_id' => $category->id]) }}" 
                   class="px-4 py-2 font-semibold transition whitespace-nowrap rounded-full {{ ($selectedCategory && $selectedCategory->id == $category->id) ? 'bg-brand text-white' : 'text-gray-500 hover:text-brand' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ===== PRODUCTS ===== --}}
    <section class="pb-16">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold text-gray-800">
            @if($selectedCategory)
                สินค้าในหมวดหมู่: <span class="text-brand">{{ $selectedCategory->name }}</span>
            @else
                สินค้าล่าสุด
            @endif
          </h2>
        </div>

        @if(isset($featured) && $featured->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
              @foreach($featured as $p)
                <div class="group bg-white rounded-2xl border shadow-sm overflow-hidden hover:shadow-xl transition-shadow duration-300">
                  <div class="relative">
                    <a href="{{ route('products.show',$p) }}" class="block overflow-hidden">
                      <img src="{{ $p->image_url }}" alt="{{ $p->name }}" class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-500">
                    </a>
                    @auth
                        @if(!auth()->user()->is_admin)
                            <form action="{{ route('wishlist.toggle', $p->id) }}" method="POST" class="absolute top-2 right-2">
                                @csrf
                                <button type="submit" class="p-2 rounded-full bg-white/70 hover:bg-white backdrop-blur-sm shadow">
                                    @if(in_array($p->id, $wishlistIds))
                                        {{-- Solid Heart (Corrected) --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-brand">
                                            <path fill-rule="evenodd" d="M6.32 2.577a4.5 4.5 0 016.364 0l.086.086a4.5 4.5 0 016.364 6.364l-6.5 6.5a.75.75 0 01-1.06 0l-6.5-6.5a4.5 4.5 0 010-6.364l.086-.086z" clip-rule="evenodd" />
                                        </svg>
                                    @else
                                        {{-- Outline Heart --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-500">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                        </svg>
                                    @endif
                                </button>
                            </form>
                        @endif
                    @endauth
                  </div>
                  <div class="p-4">
                    @if($p->category)
                        <p class="text-sm text-gray-500 mb-1">{{ $p->category->name }}</p>
                    @endif
                    <a href="{{ route('products.show',$p) }}" class="font-semibold text-lg text-gray-800 line-clamp-1 hover:text-brand">{{ $p->name }}</a>
                    <p class="text-brand font-bold text-xl mt-2">฿{{ number_format($p->price,2) }}</p>

                    <div class="mt-2">
                        @if($p->stock > 0)
                            <span class="text-xs text-gray-500">คงเหลือ: {{ $p->stock }} ชิ้น</span>
                        @endif
                    </div>
                    
                    <div class="mt-4">
                        @auth
                            @if($p->stock > 0)
                                <div class="flex items-center gap-2">
                                  <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                                      @csrf
                                      <input type="hidden" name="product_id" value="{{ $p->id }}">
                                      <input type="hidden" name="qty" value="1">
                                      <button class="w-full px-4 py-2 rounded-lg bg-brand text-white font-semibold hover:bg-brand-dark transition-colors text-sm">
                                        เพิ่มลงตะกร้า
                                      </button>
                                  </form>
                                  <form action="{{ route('buy-now.submit') }}" method="POST" class="flex-1">
                                      @csrf
                                      <input type="hidden" name="product_id" value="{{ $p->id }}">
                                      <input type="hidden" name="qty" value="1">
                                      <button class="w-full px-4 py-2 rounded-lg bg-pink-100 text-brand font-semibold hover:bg-pink-200 transition-colors text-sm">
                                        สั่งซื้อเลย
                                      </button>
                                  </form>
                                </div>
                            @else
                                <button class="w-full px-4 py-2 rounded-lg bg-gray-300 text-gray-500 cursor-not-allowed" disabled>สินค้าหมด</button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">
                                เข้าสู่ระบบเพื่อสั่งซื้อ
                            </a>
                        @endauth
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
        @else
            <div class="text-center py-12 text-gray-500">
                <p>ไม่พบสินค้าในหมวดหมู่นี้</p>
            </div>
        @endif
    </section>
</div>
@endsection
