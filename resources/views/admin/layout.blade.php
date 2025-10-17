@extends('layouts.app')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8">

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
          <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}"
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
        // Mobile sidebar toggle
        const toggleBtn = document.getElementById('adminSidebarToggle');
        const sidebar = document.getElementById('adminSidebar');
        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', function () {
                sidebar.classList.toggle('hidden');
            });
        }

        // Notification dropdown
        const bell = document.getElementById('notification-bell');
        const menu = document.getElementById('notification-menu');
        const countBadge = document.getElementById('notification-count');
        const itemsContainer = document.getElementById('notification-items');
        const notificationDropdown = document.getElementById('notification-dropdown');

        if (bell && menu && countBadge && itemsContainer && notificationDropdown) {
            bell.addEventListener('click', function(event) {
                event.stopPropagation();
                menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
            });

            document.addEventListener('click', function(event) {
                if (!notificationDropdown.contains(event.target)) {
                    menu.style.display = 'none';
                }
            });

            function fetchNotifications() {
                fetch('{{ route('admin.notifications.index') }}')
                    .then(response => response.json())
                    .then(data => {
                        // Update badge
                        if (data.count > 0) {
                            countBadge.textContent = data.count;
                            countBadge.style.display = 'flex';
                        } else {
                            countBadge.style.display = 'none';
                        }

                        // Update dropdown items
                        itemsContainer.innerHTML = ''; // Clear old items
                        if (data.notifications.length > 0) {
                            data.notifications.forEach(notification => {
                                const item = document.createElement('a');
                                item.href = `/admin/notifications/${notification.id}/read`;
                                item.className = 'block p-3 hover:bg-gray-50';
                                item.innerHTML = `
                                    <div class="flex items-start gap-3">
                                        <div class="w-2 h-2 mt-1.5 rounded-full bg-red-500"></div>
                                        <div>
                                            <p class="text-sm font-semibold">New Order #${notification.id}</p>
                                            <p class="text-xs text-gray-500">Total: ${parseFloat(notification.total_amount).toFixed(2)}</p>
                                            <p class="text-xs text-gray-500">${new Date(notification.created_at).toLocaleString()}</p>
                                        </div>
                                    </div>
                                `;

                                item.addEventListener('click', (e) => {
                                    e.preventDefault();
                                    const form = document.createElement('form');
                                    form.method = 'POST';
                                    form.action = item.href;
                                    form.innerHTML = '@csrf';
                                    document.body.appendChild(form);
                                    form.submit();
                                });
                                itemsContainer.appendChild(item);
                            });
                        } else {
                            itemsContainer.innerHTML = '<div class="p-3 text-center text-gray-500 text-sm">ไม่มีการแจ้งเตือนใหม่</div>';
                        }
                    })
                    .catch(error => console.error('Error fetching notifications:', error));
            }

            // Initial fetch and periodic update
            fetchNotifications();
            setInterval(fetchNotifications, 15000); // every 15 seconds
        }
    });
</script>
@endpush
@endsection
