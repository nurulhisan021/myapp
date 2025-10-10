@extends('layouts.app')
@section('title','ช็อป')

@section('content')
{{-- HERO แบบเต็มจอแนวนอน --}}
<section class="relative -mx-4 sm:-mx-6 mb-10">
  <div class="relative w-full overflow-hidden bg-gradient-to-r from-pink-500 to-rose-500 text-white">
    <img src="{{ asset('images/hero-1.jpg') }}" onerror="this.style.display='none'"
         class="absolute inset-0 w-full h-[42vw] max-h-[560px] min-h-[260px] object-cover opacity-60">
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 h-[42vw] max-h-[560px] min-h-[260px] flex items-center">
      <div>
        <h1 class="text-3xl sm:text-5xl font-extrabold drop-shadow">ช็อปความสวยที่ใช่สำหรับคุณ</h1>
        <p class="mt-3 sm:mt-4 text-white/90 text-lg">รวมสกินแคร์ & เมคอัพ ราคาดี จัดส่งไว พร้อมโปรเพียบ</p>
        <div class="mt-6 flex items-center gap-3">
          <a href="{{ route('products.index') }}" class="px-5 py-3 rounded-xl bg-white text-brand font-semibold shadow hover:bg-pink-50">เริ่มช็อปเลย</a>
          <a href="{{ route('cart.index') }}" class="px-5 py-3 rounded-xl border border-white/70 text-white hover:bg-white/10">
            ตะกร้า @if(collect(session('cart',[]))->sum('qty')) ({{ collect(session('cart',[]))->sum('qty') }}) @endif
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- หมวดหมู่ --}}
<section class="mb-10">
  <h2 class="text-xl font-semibold mb-4">หมวดหมู่ยอดนิยม</h2>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    @foreach([['ครีม','from-rose-100 to-pink-50'],['ลิป','from-pink-100 to-rose-50'],['กันแดด','from-fuchsia-100 to-pink-50']] as [$kw,$grad])
      <a href="{{ route('products.index',['q'=>$kw]) }}"
         class="group relative overflow-hidden rounded-2xl border bg-white">
        <div class="aspect-[16/9] w-full bg-gradient-to-tr {{ $grad }}"></div>
        <div class="absolute inset-0 flex items-end p-4">
          <span class="px-3 py-1.5 rounded-lg bg-white text-brand font-medium group-hover:shadow">ค้นหา “{{ $kw }}”</span>
        </div>
      </a>
    @endforeach
  </div>
</section>

{{-- สินค้ายอดนิยม (ส่ง $featured มาจาก ShopController) --}}
@if(isset($featured) && $featured->isNotEmpty())
  <section>
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-xl font-semibold">สินค้ายอดนิยม</h2>
      <a href="{{ route('products.index') }}" class="text-sm text-gray-600 hover:underline">ดูทั้งหมด →</a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      @foreach($featured as $p)
        <div class="bg-white rounded-2xl border shadow-sm overflow-hidden hover:shadow-lg transition">
          <a href="{{ route('products.show',$p) }}">
            <img src="{{ $p->image_url }}" class="w-full aspect-[4/3] object-cover" alt="{{ $p->name }}">
          </a>
          <div class="p-4">
            <a href="{{ route('products.show',$p) }}" class="font-medium line-clamp-1">{{ $p->name }}</a>
            <p class="text-brand font-semibold mt-1">{{ number_format($p->price,2) }} บาท</p>
            <p class="text-sm text-gray-500 mt-2 line-clamp-2">{{ $p->description }}</p>

            {{-- ปุ่มใส่ตะกร้า (ลูกค้า) --}}
            <form action="{{ route('cart.add') }}" method="POST" class="mt-3 flex items-center gap-2">
              @csrf
              <input type="hidden" name="product_id" value="{{ $p->id }}">
              <input type="number" name="qty" value="1" min="1" class="w-16 rounded-lg border-gray-300 text-center">
              <button class="px-3 py-1.5 rounded-lg bg-brand text-white hover:bg-brand-dark">🛒 ใส่ตะกร้า</button>
            </form>
          </div>
        </div>
      @endforeach
    </div>
  </section>
@else
  <div class="bg-white border rounded-xl p-6 text-gray-500">ยังไม่มีสินค้า ลองเพิ่มสินค้าที่หน้าแอดมินก่อนนะคะ</div>
@endif
@endsection
