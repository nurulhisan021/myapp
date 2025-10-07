<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','MyShop')</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50">
  <nav class="bg-white border-b">
  <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">
    <a href="{{ url('/') }}" class="font-semibold text-lg">🛍️ MyShop</a>

    <div class="flex items-center gap-4">
      <a class="text-sm text-gray-600" href="{{ route('products.index') }}">สินค้า</a>
      <a class="text-sm text-gray-600" href="{{ route('cart.index') }}">ตะกร้า</a>

      @auth
        <a class="text-sm text-gray-600" href="{{ route('account.home') }}">
          สวัสดี, {{ Str::limit(auth()->user()->name, 12) }}
        </a>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="text-sm text-gray-600 hover:underline">ออกจากระบบ</button>
        </form>
      @else
        <a class="text-sm text-gray-600" href="{{ route('login') }}">เข้าสู่ระบบ</a>
        <a class="text-sm text-gray-600" href="{{ route('register') }}">สมัครสมาชิก</a>
      @endauth
    </div>
  </div>
</nav>

  <main class="max-w-5xl mx-auto p-4">
    @if(session('ok'))
      <div class="mb-4 rounded bg-green-50 border border-green-200 text-green-800 px-4 py-3">
        {{ session('ok') }}
      </div>
    @endif
    @yield('content')
  </main>
  <footer class="max-w-5xl mx-auto px-4 py-6 text-center text-xs text-gray-500">
    © {{ date('Y') }} MyShop
  </footer>
</body>
</html>
