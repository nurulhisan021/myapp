@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Mobile Sidebar Toggle --}}
    <div class="md:hidden mb-4">
        <button id="adminSidebarToggle" class="flex items-center gap-2 px-4 py-2 border rounded-lg font-semibold">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" /></svg>
            เมนูแอดมิน
        </button>
    </div>

    <div class="flex flex-col md:flex-row gap-8">

      {{-- Admin Sidebar --}}
      <aside id="adminSidebar" class="w-full md:w-64 flex-shrink-0 hidden md:block">
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
          <a href="{{ route('admin.admins.index') }}"
             class="{{ request()->routeIs('admin.admins.*') ? 'text-brand font-bold' : '' }} hover:text-brand">
             จัดการแอดมิน
          </a>
        </nav>
      </aside>

      {{-- Main Content --}}
      <div class="flex-1">
        @yield('admin_content')
      </div>

    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('adminSidebarToggle');
        const sidebar = document.getElementById('adminSidebar');
        
        toggleBtn.addEventListener('click', function () {
            sidebar.classList.toggle('hidden');
        });
    });
</script>
@endpush
@endsection
