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
        <div class="w-full px-4 sm:px-6 h-16 flex items-center justify-between gap-4">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 font-bold text-lg">
                <span>🛍️</span><span>MyShop (Admin)</span>
            </a>
            <div class="flex items-center gap-4">
                <!-- Notification Dropdown -->
                <div class="relative" id="notification-dropdown">
                    <!-- Bell Icon -->
                    <button id="notification-bell" class="relative text-gray-600 hover:text-gray-800 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V5a1 1 0 00-2 0v.083A6 6 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <!-- Notification Badge -->
                        <span id="notification-count" class="absolute -top-1 -right-1 h-4 w-4 rounded-full bg-red-500 text-white text-xs flex items-center justify-center" style="display: none;">0</span>
                    </button>
                    <!-- Dropdown Menu -->
                    <div id="notification-menu" class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border overflow-hidden" style="display: none;">
                        <div class="p-3 border-b">
                            <h3 class="font-semibold text-sm">การแจ้งเตือน</h3>
                        </div>
                        <div id="notification-items" class="divide-y max-h-96 overflow-y-auto">
                            <!-- Notification items will be injected here by JavaScript -->
                            <div class="p-3 text-center text-gray-500 text-sm">ไม่มีการแจ้งเตือนใหม่</div>
                        </div>
                        <div class="p-2 bg-gray-50 text-center">
                            <a href="{{ route('admin.orders.index') }}" class="text-sm text-brand hover:underline">ดูออเดอร์ทั้งหมด</a>
                        </div>
                    </div>
                </div>

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
        <div class="w-full px-4 sm:px-6 h-16 flex items-center justify-between gap-4">

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
              <a href="{{ route('account.profile.edit') }}" class="text-sm hover:text-brand">แก้ไขโปรไฟล์</a>
              <a href="{{ route('wishlist.index') }}" class="text-sm hover:text-brand">สินค้าที่อยากได้</a>
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
          <div class="w-full px-4 sm:px-6 py-3 flex flex-col gap-3 text-sm">
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
              <div class="border-t pt-2">
                  <p class="text-sm text-gray-500">บัญชีของฉัน: {{ auth()->user()->name }}</p>
                  <a href="{{ route('account.orders.index') }}" class="hover:text-brand block py-1">ประวัติคำสั่งซื้อ</a>
                  <a href="{{ route('account.addresses.index') }}" class="hover:text-brand block py-1">จัดการที่อยู่</a>
                  <a href="{{ route('account.profile.edit') }}" class="hover:text-brand block py-1">แก้ไขโปรไฟล์</a>
                  <a href="{{ route('wishlist.index') }}" class="hover:text-brand block py-1">สินค้าที่อยากได้</a>
              </div>
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
    <div class="w-full px-4 sm:px-6 mt-4">
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
    <div class="w-full px-4 sm:px-6 py-8 text-sm text-gray-500 text-center">
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
