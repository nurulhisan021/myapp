<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <title>@yield('title','MyShop')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- Tailwind CDN --}}
  <script src="https://cdn.tailwindcss.com"></script>
  {{-- Alpine.js CDN --}}
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
          </nav>

          {{-- Right: auth actions (desktop) --}}
          <div class="hidden md:flex items-center gap-4">
            {{-- Cart Icon --}}
            <a href="{{ route('cart.index') }}" class="relative p-2 rounded-full bg-white/50 backdrop-blur-md border border-white/80 shadow-sm text-gray-700 hover:bg-white hover:text-brand transition-all duration-300 ease-in-out">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              @if($cartCount)
                <span class="absolute -top-1 -right-1 px-2 py-0.5 rounded-full bg-brand text-white text-xs font-bold">{{ $cartCount }}</span>
              @endif
            </a>

            @auth
              @if(auth()->user()->is_admin)
                <a href="{{ route('admin.dashboard') }}"
                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-sm">
                  แอดมิน
                </a>
              @endif

              {{-- User Dropdown --}}
              <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.away="open = false" class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </button>

                <div x-show="open" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border py-1 z-10">
                    <div class="px-4 py-2 border-b">
                        <p class="text-sm font-semibold text-gray-700">สวัสดี, {{ auth()->user()->name }}</p>
                    </div>
                    <a href="{{ route('account.orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-brand">ประวัติคำสั่งซื้อ</a>
                    <a href="{{ route('account.addresses.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-brand">จัดการที่อยู่</a>
                    <a href="{{ route('account.profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-brand">แก้ไขโปรไฟล์</a>
                    <a href="{{ route('wishlist.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-brand">สินค้าที่อยากได้</a>
                    <div class="border-t"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-brand">ออกจากระบบ</button>
                    </form>
                </div>
              </div>

            @endauth

            @guest
              <a href="{{ route('login') }}" class="text-sm hover:text-brand">เข้าสู่ระบบ</a>
              <a href="{{ route('register') }}"
                 class="px-4 py-2 rounded-xl bg-brand text-white hover:bg-brand-dark text-sm">
                สมัครสมาชิก
              </a>
            @endguest
          </div>

          <div class="md:hidden flex items-center gap-2">
            {{-- Cart Icon --}}
            <a href="{{ route('cart.index') }}" class="relative p-2 rounded-full bg-white/50 backdrop-blur-md border border-white/80 shadow-sm text-gray-700 hover:bg-white hover:text-brand transition-all duration-300 ease-in-out">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              @if($cartCount)
                <span class="absolute -top-1 -right-1 px-2 py-0.5 rounded-full bg-brand text-white text-xs font-bold">{{ $cartCount }}</span>
              @endif
            </a>

            @auth
              {{-- User Dropdown --}}
              <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.away="open = false" class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </button>

                <div x-show="open" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border py-1 z-10">
                    <div class="px-4 py-2 border-b">
                        <p class="text-sm font-semibold text-gray-700">สวัสดี, {{ auth()->user()->name }}</p>
                    </div>
                    <a href="{{ route('account.orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-brand">ประวัติคำสั่งซื้อ</a>
                    <a href="{{ route('account.addresses.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-brand">จัดการที่อยู่</a>
                    <a href="{{ route('account.profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-brand">แก้ไขโปรไฟล์</a>
                    <a href="{{ route('wishlist.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-brand">สินค้าที่อยากได้</a>
                    <div class="border-t"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-brand">ออกจากระบบ</button>
                    </form>
                </div>
              </div>
            @endauth
          </div>
        </div>

    </header>
  @endif

  {{-- Flash message --}}
    <div class="fixed top-5 right-5 z-50 space-y-2">
        @if(session('ok'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
            class="relative max-w-sm rounded-lg border border-green-200 bg-green-50 text-green-800 px-4 py-3 pr-10 shadow-lg"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-x-8"
            x-transition:enter-end="opacity-100 transform translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-x-0"
            x-transition:leave-end="opacity-0 transform translate-x-8">
            <p>{{ session('ok') }}</p>
            <button @click="show = false" class="absolute top-1 right-1 p-1 text-green-800/60 hover:text-green-800/90">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"></path></svg>
            </button>
        </div>
        @endif
        @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 8000)"
            class="relative max-w-sm rounded-lg border border-red-200 bg-red-50 text-red-700 px-4 py-3 pr-10 shadow-lg"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-x-8"
            x-transition:enter-end="opacity-100 transform translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-x-0"
            x-transition:leave-end="opacity-0 transform translate-x-8">
            <p>{{ session('error') }}</p>
            <button @click="show = false" class="absolute top-1 right-1 p-1 text-red-700/60 hover:text-red-700/90">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"></path></svg>
            </button>
        </div>
        @endif
    </div>

  {{-- Content --}}
  <main class="py-8 flex-grow">
    @yield('content')
  </main>

  {{-- Footer --}}
  <footer class="border-t bg-white">
    <div class="w-full px-4 sm:px-6 py-8 text-sm text-gray-500 text-center">
      <p>© {{ now()->translatedFormat('Y') }} MyShop — All rights reserved.</p>
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
