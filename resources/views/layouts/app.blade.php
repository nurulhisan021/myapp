<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <title>@yield('title','MyShop')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  {{-- Tailwind CDN --}}
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: { DEFAULT: '#ec4899', dark: '#db2777' }, // pink
          }
        }
      }
    }
  </script>
</head>
<body class="min-h-screen bg-gray-50 text-gray-900 antialiased flex flex-col">
  @php
    $cartCount = collect(session('cart',[]))->sum('qty');
  @endphp

  {{-- Conditionally render header based on route --}}
  @if(request()->routeIs('admin.*'))
    {{-- Admin Header --}}
    <header class="sticky top-0 z-40 bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-4">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 font-bold text-lg">
                <span>🛍️</span><span>MyShop (Admin)</span>
            </a>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-500">{{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="px-3 py-1.5 rounded-lg border hover:bg-gray-50 text-sm">ออกจากระบบ</button>
                </form>
            </div>
        </div>
    </header>
  @else
    {{-- Public Header --}}
    <header class="sticky top-0 z-40 bg-white/80 backdrop-blur border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-4">

          {{-- Left: Brand --}}
          <a href="{{ route('shop.home') }}" class="flex items-center gap-2 font-bold text-lg">
            <span>🛍️</span><span>MyShop</span>
          </a>

          {{-- Center: main menu (desktop) --}}
          <nav class="hidden md:flex items-center gap-6 text-sm">
            <a href="{{ route('products.index') }}" class="hover:text-brand">สินค้า</a>
            <a href="{{ route('cart.index') }}" class="hover:text-brand flex items-center gap-2">
              <span>ตะกร้า</span>
              @if($cartCount)
                <span class="px-2 py-0.5 rounded-full bg-brand text-white text-xs">{{ $cartCount }}</span>
              @endif
            </a>
          </nav>

          {{-- Right: auth actions (desktop) --}}
          <div class="hidden md:flex items-center gap-3">
            @auth
              @if(auth()->user()->is_admin)
                <a href="{{ route('admin.dashboard') }}"
                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-sm">
                  แอดมิน
                </a>
              @endif
                            <span class="text-sm text-gray-600">สวัสดี, {{ auth()->user()->name }}</span>
              <a href="{{ route('account.orders.index') }}" class="text-sm hover:text-brand">ประวัติคำสั่งซื้อ</a>
              <a href="{{ route('account.addresses.index') }}" class="text-sm hover:text-brand">จัดการที่อยู่</a>
              <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="px-3 py-1.5 rounded-lg border hover:bg-gray-50 text-sm">ออกจากระบบ</button>
              </form>
            @endauth

            @guest
              <a href="{{ route('login') }}" class="text-sm hover:text-brand">เข้าสู่ระบบ</a>
              <a href="{{ route('register') }}"
                 class="px-4 py-2 rounded-xl bg-brand text-white hover:bg-brand-dark text-sm">
                สมัครสมาชิก
              </a>
            @endguest
          </div>

          {{-- Mobile menu button --}}
          <button id="mobileMenuBtn"
                  class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg border">
            ☰
          </button>
        </div>

        {{-- Mobile drawer --}}
        <div id="mobileMenu" class="md:hidden hidden border-t bg-white">
          <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 flex flex-col gap-3 text-sm">
            <a href="{{ route('products.index') }}" class="hover:text-brand">สินค้า</a>
            <a href="{{ route('cart.index') }}" class="hover:text-brand flex items-center gap-2">
              <span>ตะกร้า</span>
              @if($cartCount)
                <span class="px-2 py-0.5 rounded-full bg-brand text-white text-xs">{{ $cartCount }}</span>
              @endif
            </a>

            @auth
              @if(auth()->user()->is_admin)
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand">แอดมิน</a>
              @endif
              <a href="{{ route('account.orders.index') }}" class="hover:text-brand">บัญชีของฉัน: {{ auth()->user()->name }}</a>
              <form action="{{ route('logout') }}" method="POST" class="pt-2 border-t">
                @csrf
                <button class="px-3 py-2 rounded-lg border hover:bg-gray-50 w-full text-left">ออกจากระบบ</button>
              </form>
            @endauth

            @guest
              <a href="{{ route('login') }}" class="hover:text-brand">เข้าสู่ระบบ</a>
              <a href="{{ route('register') }}"
                 class="px-3 py-2 rounded-lg bg-brand text-white hover:bg-brand-dark text-center">
                สมัครสมาชิก
              </a>
            @endguest
          </div>
        </div>
    </header>
  @endif

  {{-- Flash message --}}
  @if(session('ok') || session('error'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 mt-4">
      @if(session('ok'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 text-green-800 px-4 py-3">
          {{ session('ok') }}
        </div>
      @endif
      @if(session('error'))
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 text-red-700 px-4 py-3">
          {{ session('error') }}
        </div>
      @endif
    </div>
  @endif

  {{-- Content --}}
  <main class="py-8 flex-grow">
    @yield('content')
  </main>

  {{-- Footer --}}
  <footer class="border-t bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 text-sm text-gray-500 text-center">
      <p>© {{ date('Y') }} MyShop — All rights reserved.</p>
    </div>
  </footer>

  {{-- Scripts --}}
  <script>
    // toggle เมนูมือถือ
    const btn = document.getElementById('mobileMenuBtn');
    const panel = document.getElementById('mobileMenu');
    btn?.addEventListener('click', () => panel.classList.toggle('hidden'));
  </script>
  @stack('scripts')
</body>
</html>
