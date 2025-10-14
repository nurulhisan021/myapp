@extends('layouts.app')

@section('content')
<div class="flex gap-8">

  {{-- Admin Sidebar --}}
  <aside class="w-64 flex-shrink-0">
    <h2 class="font-bold text-lg mb-4">เมนูแอดมิน</h2>
    <nav class="flex flex-col gap-3">
      <a href="{{ route('admin.dashboard') }}" 
         class="{{ request()->routeIs('admin.dashboard') ? 'text-brand font-bold' : '' }} hover:text-brand">
         แดชบอร์ด
      </a>
      <a href="{{ route('admin.products.index') }}" 
         class="{{ request()->routeIs('admin.products.*') ? 'text-brand font-bold' : '' }} hover:text-brand">
         จัดการสินค้า
      </a>
      <a href="{{ route('admin.categories.index') }}" 
         class="{{ request()->routeIs('admin.categories.*') ? 'text-brand font-bold' : '' }} hover:text-brand">
         จัดการหมวดหมู่
      </a>
      <a href="{{ route('admin.orders.index') }}"
         class="{{ request()->routeIs('admin.orders.*') ? 'text-brand font-bold' : '' }} hover:text-brand">
         จัดการคำสั่งซื้อ
      </a>
      <a href="{{ route('admin.bank-account.index') }}"
         class="{{ request()->routeIs('admin.bank-account.index') ? 'text-brand font-bold' : '' }} hover:text-brand">
         จัดการบัญชีธนาคาร
      </a>
      {{-- Links for future features can be added here --}}
    </nav>
  </aside>

  {{-- Main Content --}}
  <div class="flex-1">
    @yield('admin_content')
  </div>

</div>
@endsection
