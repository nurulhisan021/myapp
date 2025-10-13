@extends('layouts.app')
@section('title','ช็อป')

@section('content')
@php
  $cartCount = collect(session('cart', []))->sum('qty');

  // รายการสไลด์
  $slides = [];
  foreach (['hero-1','hero-2','hero-3'] as $name) {
    foreach (['jpg','jpeg','png','webp'] as $ext) {
      $rel = "images/{$name}.{$ext}";
      if (file_exists(public_path($rel))) { $slides[] = $rel; break; }
    }
  }

  // base URL ปัจจุบัน (เช่น '' หรือ '/myapp/public')
  $base = rtrim(request()->getBaseUrl(), '/');
@endphp

@php
  // ปรับค่าตามชอบ
  $heroHeight = 'h-[380px] md:h-[520px] xl:h-[640px]'; // ความสูง
  $overlay    = 'bg-black/25';                         // ความทึบโอเวอร์เลย์
@endphp

{{-- ===== HERO: FULL-BLEED (กินขอบซ้าย-ขวา) ===== --}}
<section id="hero-carousel"
         class="relative w-screen left-1/2 -translate-x-1/2 {{ count($slides) ? '' : 'bg-gradient-to-r from-pink-500 to-rose-500' }}
                text-white rounded-none overflow-hidden mb-8">

  {{-- แทร็คสไลด์เต็มจอ --}}
  <div data-track class="flex transition-transform duration-700 ease-out" style="transform: translateX(0%);">
    @if(count($slides))
      @foreach($slides as $src)
        <div class="w-screen flex-none relative">
          <img src="{{ ($base ? $base.'/' : '/') . $src }}"
     class="w-full {{ $heroHeight ?? 'h-[380px] md:h-[520px] xl:h-[640px]' }} object-cover"
     alt="banner">
          <div class="absolute inset-0 {{ $overlay }}"></div>

          {{-- เนื้อหาตรงกลาง จำกัดความกว้างให้อ่านง่าย แต่พื้นหลังยังเต็มจอ --}}
          <div class="absolute inset-0">
            <div class="max-w-7xl mx-auto px-6 sm:px-10 h-full flex items-center">
              <div class="max-w-3xl">
                <h1 class="text-3xl sm:text-5xl font-bold drop-shadow">ช็อปความสวยที่ใช่สำหรับคุณ</h1>
                <p class="mt-3 sm:mt-4 text-white/90 text-lg">รวมสกินแคร์ & เมคอัพ ราคาดี จัดส่งไว พร้อมโปรเพียบ</p>
                <div class="mt-6 flex flex-wrap items-center gap-3">
                  <a href="{{ route('products.index') }}"
                     class="px-5 py-3 rounded-xl bg-white text-pink-600 font-semibold shadow hover:bg-pink-50">
                    เริ่มช็อปเลย
                  </a>
                  <a href="{{ route('cart.index') }}"
                     class="px-5 py-3 rounded-xl border border-white/70 text-white hover:bg-white/10">
                     ตะกร้า @if($cartCount) ({{ $cartCount }}) @endif
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    @else
      {{-- ไม่มีรูป -> ใช้กราเดียนท์เต็มจอ --}}
      <div class="w-screen flex-none relative">
        <div class="w-full {{ $heroHeight }}"></div>
        <div class="absolute inset-0">
          <div class="max-w-7xl mx-auto px-6 sm:px-10 h-full flex items-center">
            <div class="max-w-3xl">
              <h1 class="text-3xl sm:text-5xl font-bold drop-shadow">ช็อปความสวยที่ใช่สำหรับคุณ</h1>
              <p class="mt-3 sm:mt-4 text-white/90 text-lg">รวมสกินแคร์ & เมคอัพ ราคาดี จัดส่งไว พร้อมโปรเพียบ</p>
              <div class="mt-6 flex flex-wrap items-center gap-3">
                <a href="{{ route('products.index') }}"
                   class="px-5 py-3 rounded-xl bg-white text-pink-600 font-semibold shadow hover:bg-pink-50">
                  เริ่มช็อปเลย
                </a>
                <a href="{{ route('cart.index') }}"
                   class="px-5 py-3 rounded-xl border border-white/70 text-white hover:bg-white/10">
                  ตะกร้า @if($cartCount) ({{ $cartCount }}) @endif
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif
  </div>

  @if(count($slides) > 1)
    {{-- ปุ่มเลื่อนซ้าย/ขวา --}}
    <button type="button" data-prev
      class="hidden sm:flex absolute left-4 md:left-6 top-1/2 -translate-y-1/2 w-12 h-12 items-center justify-center
             rounded-full bg-black/40 text-white hover:bg-black/60">‹</button>
    <button type="button" data-next
      class="hidden sm:flex absolute right-4 md:right-6 top-1/2 -translate-y-1/2 w-12 h-12 items-center justify-center
             rounded-full bg-black/40 text-white hover:bg-black/60">›</button>

    {{-- จุดบอกตำแหน่ง --}}
    <div class="absolute bottom-5 left-0 right-0 flex justify-center gap-2">
      @foreach($slides as $i => $_)
        <button type="button" data-dot class="w-2.5 h-2.5 rounded-full bg-white/80 opacity-50"></button>
      @endforeach
    </div>
  @endif
</section>


{{-- สคริปต์คุมคารูเซล (Vanilla JS) --}}
<script>
(function(){
  const root = document.getElementById('hero-carousel');
  if(!root) return;
  const track = root.querySelector('[data-track]');
  const slides = track ? Array.from(track.children) : [];
  if(!slides.length) return;

  let i = 0, timer = null;
  const dots = Array.from(root.querySelectorAll('[data-dot]'));
  const prev = root.querySelector('[data-prev]');
  const next = root.querySelector('[data-next]');

  function go(n){
    i = (n + slides.length) % slides.length;
    track.style.transform = `translateX(${-i*100}%)`;
    dots.forEach((d,idx) => d.style.opacity = (idx===i ? '1' : '0.5'));
  }
  function autoplay(){ timer = setInterval(() => go(i+1), 5000); }
  function stop(){ if(timer){ clearInterval(timer); timer = null; } }

  prev && prev.addEventListener('click', ()=>{ stop(); go(i-1); autoplay(); });
  next && next.addEventListener('click', ()=>{ stop(); go(i+1); autoplay(); });
  dots.forEach((d,idx)=> d.addEventListener('click', ()=>{ stop(); go(idx); autoplay(); }));

  root.addEventListener('mouseenter', stop);
  root.addEventListener('mouseleave', autoplay);

  go(0); autoplay();
})();
</script>

{{-- ===== หมวดหมู่ ===== --}}
@if(isset($categories) && $categories->isNotEmpty())
<section class="relative w-screen left-1/2 -translate-x-1/2 mb-12">
  <div class="w-full px-4 sm:px-6 lg:px-10">
    <h2 class="text-xl font-semibold mb-4">เลือกซื้อตามหมวดหมู่</h2>

    <div class="flex items-center gap-6 border-b">
        <a href="{{ route('shop.home') }}" 
           class="px-1 py-4 font-semibold transition whitespace-nowrap {{ !$selectedCategory ? 'text-brand border-b-2 border-brand' : 'text-gray-500 hover:text-brand' }}">
            ทั้งหมด
        </a>
        @foreach ($categories as $category)
            <a href="{{ route('shop.home', ['category_id' => $category->id]) }}" 
               class="px-3 py-4 font-semibold transition whitespace-nowrap {{ ($selectedCategory && $selectedCategory->id == $category->id) ? 'text-brand border-b-2 border-brand' : 'text-gray-500 hover:text-brand' }}">
                {{ $category->name }}
            </a>
        @endforeach
    </div>
  </div>
</section>
@endif


{{-- ===== สินค้ายอดนิยม ===== --}}
@if(isset($featured) && $featured->isNotEmpty())
<section class="relative w-screen left-1/2 -translate-x-1/2 mb-16">
  <div class="w-full px-4 sm:px-6 lg:px-10">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-xl font-semibold">
        @if($selectedCategory)
            สินค้าในหมวดหมู่: {{ $selectedCategory->name }}
        @else
            สินค้าล่าสุด
        @endif
      </h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
      @foreach($featured as $p)
        <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
          <a href="{{ route('products.show',$p) }}">
            <img src="{{ $p->image_url }}" alt="{{ $p->name }}" class="w-full h-48 object-cover">
          </a>
          <div class="p-4">
            <a href="{{ route('products.show',$p) }}" class="font-medium block truncate">{{ $p->name }}</a>
            <p class="text-pink-600 font-semibold mt-1">{{ number_format($p->price,2) }} บาท</p>
            <p class="text-sm text-gray-500 mt-2 truncate">{{ $p->description }}</p>

            @auth
                @if($p->stock > 0)
                    <form action="{{ route('cart.add') }}" method="POST" class="mt-3 flex items-center gap-2">
                      @csrf
                      <input type="hidden" name="product_id" value="{{ $p->id }}">
                      <input type="number" name="qty" value="1" min="1" max="{{ $p->stock }}"
                             class="w-16 rounded-lg border-gray-300 text-center">
                      <button class="px-3 py-1.5 rounded-lg bg-pink-600 text-white hover:bg-pink-700">
                        🛒 ใส่ตะกร้า
                      </button>
                    </form>
                @else
                    <button class="mt-3 w-full px-3 py-1.5 rounded-lg bg-gray-300 text-gray-500 cursor-not-allowed" disabled>สินค้าหมด</button>
                @endif
            @else
                <a href="{{ route('login') }}" class="mt-3 block w-full text-center px-3 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">
                    เข้าสู่ระบบเพื่อสั่งซื้อ
                </a>
            @endauth
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

@endsection
