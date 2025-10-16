@extends('layouts.app')
@section('title','สินค้า')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="flex items-center justify-between mb-6 gap-3">
  <form action="{{ route('products.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
    {{-- Search Input --}}
    <div class="flex-grow sm:flex-grow-0">
        <input type="text" name="search" value="{{ $request->input('search') }}" placeholder="ค้นหาชื่อสินค้า"
               class="w-full sm:w-64 rounded-lg border-gray-300 focus:ring-2 focus:ring-brand">
    </div>

    {{-- Category Filter --}}
    <div>
        <select name="category" onchange="this.form.submit()" class="rounded-lg border-gray-300 focus:ring-2 focus:ring-brand">
            <option value="">ทุกหมวดหมู่</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ $request->input('category') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Sort Options --}}
    <div>
        <select name="sort" onchange="this.form.submit()" class="rounded-lg border-gray-300 focus:ring-2 focus:ring-brand">
            <option value="" {{ !$request->input('sort') ? 'selected' : '' }}>เรียงตาม: ใหม่ล่าสุด</option>
            <option value="price_asc" {{ $request->input('sort') == 'price_asc' ? 'selected' : '' }}>เรียงตาม: ราคาต่ำไปสูง</option>
            <option value="price_desc" {{ $request->input('sort') == 'price_desc' ? 'selected' : '' }}>เรียงตาม: ราคาสูงไปต่ำ</option>
        </select>
    </div>

    {{-- Buttons --}}
    <div class="flex items-center gap-2">
        <button type="submit" class="px-4 py-2 rounded-lg bg-gray-900 text-white hover:bg-black/90">ค้นหา</button>
        <a href="{{ route('products.index') }}" class="px-4 py-2 rounded-lg border hover:bg-gray-50">ล้างค่า</a>
    </div>
</form>

  @if(session('is_admin'))
    <a href="{{ route('admin.products.create') }}" class="px-4 py-2 rounded-xl bg-brand text-white hover:bg-brand-dark">
      + เพิ่มสินค้า
    </a>
  @endif
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
  @forelse($products as $p)
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
                                                                        @else                                                    {{-- Outline Heart --}}
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-500">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                                    </svg>
                                                @endif                    </button>
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
                @if(auth()->user()->is_admin)
                    {{-- Admin Buttons --}}
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.products.edit', $p) }}" class="px-3 py-1.5 rounded-lg border hover:bg-gray-50 text-sm flex-1 text-center">แก้ไข</a>
                        <form action="{{ route('admin.products.destroy', $p) }}" method="POST" onsubmit="return confirm('ยืนยันลบ?')" class="flex-1">
                            @csrf @method('DELETE')
                            <button class="w-full px-3 py-1.5 rounded-lg bg-red-600 text-white hover:bg-red-700 text-sm">ลบ</button>
                        </form>
                    </div>
                @else
                    {{-- User Buttons --}}
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
                @endif
            @else
                {{-- Guest Button --}}
                <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">
                    เข้าสู่ระบบเพื่อสั่งซื้อ
                </a>
            @endauth
        </div>
      </div>
    </div>
  @empty
    <p class="text-gray-500 col-span-full text-center">ยังไม่มีสินค้า</p>
  @endforelse
</div>

<div class="mt-6">{{ $products->links() }}</div>
</div>
@endsection
