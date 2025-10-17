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
      <aside id="adminSidebar" class="w-full md:w-64 bg-white text-gray-800 p-6 rounded-lg shadow-md border border-gray-200 hidden md:block">
        <h2 class="font-bold text-xl mb-6 border-b border-gray-200 pb-4">Admin Panel</h2>
        <nav class="flex flex-col gap-4">

          <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 text-gray-900 font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" /></svg>
            <span>แดชบอร์ด</span>
          </a>

          <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.products.*') ? 'bg-gray-100 text-gray-900 font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1H8a3 3 0 00-3 3v1.586l-1.293 1.293a1 1 0 001.414 1.414L6 10.414V16a1 1 0 001 1h6a1 1 0 001-1v-5.586l.293.293a1 1 0 001.414-1.414L14 8.586V7a3 3 0 00-3-3h-1V3a1 1 0 00-1-1zm0 4a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" /></svg>
            <span>จัดการสินค้า</span>
          </a>

          <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-gray-100 text-gray-900 font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" /></svg>
            <span>จัดการหมวดหมู่</span>
          </a>

          <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-gray-100 text-gray-900 font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" /><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" /></svg>
            <span>จัดการคำสั่งซื้อ</span>
          </a>

          {{-- Settings Section --}}
          <div class="mt-4 pt-4 border-t border-gray-200">
            <h3 class="font-semibold text-lg mb-4 text-gray-500">ตั้งค่า</h3>
            <div class="flex flex-col gap-4">
              <a href="{{ route('admin.bank-account.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.bank-account.index') ? 'bg-gray-100 text-gray-900 font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" /></svg>
                <span>จัดการบัญชีธนาคาร</span>
              </a>
    
              <a href="{{ route('admin.admins.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.admins.*') ? 'bg-gray-100 text-gray-900 font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" /></svg>
                <span>จัดการแอดมิน</span>
              </a>
            </div>
          </div>

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
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
                                const isRead = notification.read_at !== null;
                                const item = document.createElement('div'); // Use div instead of <a> to handle click manually
                                item.className = 'block p-3 hover:bg-gray-50 cursor-pointer';
                                
                                // The URL to mark as read
                                const markAsReadUrl = `/admin/notifications/${notification.id}/read`;
                                // The URL to navigate to
                                const orderUrl = `/admin/orders/${notification.id}`;

                                item.innerHTML = `
                                    <div class="flex items-start gap-3">
                                        <div class="red-dot w-2 h-2 mt-1.5 rounded-full bg-red-500" style="visibility: ${isRead ? 'hidden' : 'visible'}"></div>
                                        <div>
                                            <p class="text-sm font-semibold">New Order #${notification.id}</p>
                                            <p class="text-xs text-gray-500">Total: ${parseFloat(notification.total_amount).toFixed(2)}</p>
                                            <p class="text-xs text-gray-500">${new Date(notification.created_at).toLocaleString()}</p>
                                        </div>
                                    </div>
                                `;

                                item.addEventListener('click', (e) => {
                                    e.preventDefault();
                                    
                                    // Hide the dot immediately for better UX
                                    const redDot = item.querySelector('.red-dot');
                                    if (redDot) {
                                        redDot.style.visibility = 'hidden';
                                    }

                                    // Mark as read in the background
                                    fetch(markAsReadUrl, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': csrfToken
                                        },
                                    }).then(res => res.json()).then(data => {
                                        // After marking as read, navigate to the order
                                        window.location.href = orderUrl;
                                    }).catch(error => {
                                        console.error('Error marking notification as read:', error);
                                        // Still navigate even if the mark as read fails
                                        window.location.href = orderUrl;
                                    });
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
