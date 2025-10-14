@extends('admin.layout')
@section('title', 'แดชบอร์ด')

@section('admin_content')
    <h1 class="text-2xl font-bold mb-6">แดชบอร์ดและภาพรวม</h1>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white border rounded-lg shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-500">ยอดขายทั้งหมด</h3>
            <p class="text-3xl font-bold mt-1">฿{{ number_format($totalRevenue, 2) }}</p>
        </div>
        <div class="bg-white border rounded-lg shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-500">ยอดขายวันนี้</h3>
            <p class="text-3xl font-bold mt-1">฿{{ number_format($todayRevenue, 2) }}</p>
        </div>
        <div class="bg-white border rounded-lg shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-500">ออเดอร์ทั้งหมด</h3>
            <p class="text-3xl font-bold mt-1">{{ number_format($totalOrders) }}</p>
        </div>
        <div class="bg-white border rounded-lg shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-500">ออเดอร์วันนี้</h3>
            <p class="text-3xl font-bold mt-1">{{ number_format($todayOrders) }}</p>
        </div>
    </div>

    {{-- Orders Table --}}
    <div class="bg-white border rounded-lg shadow-sm">
        <div class="p-4 border-b">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold">รายการออเดอร์</h3>
                {{-- Filter Tabs --}}
                <div class="flex items-center gap-4 text-sm">
                    <a href="{{ route('admin.dashboard', ['period' => 'all']) }}" class="{{ $period == 'all' ? 'font-bold text-brand' : 'text-gray-500 hover:text-brand' }}">ทั้งหมด</a>
                    <a href="{{ route('admin.dashboard', ['period' => 'today']) }}" class="{{ $period == 'today' ? 'font-bold text-brand' : 'text-gray-500 hover:text-brand' }}">วันนี้</a>
                    <a href="{{ route('admin.dashboard', ['period' => 'week']) }}" class="{{ $period == 'week' ? 'font-bold text-brand' : 'text-gray-500 hover:text-brand' }}">สัปดาห์นี้</a>
                    <a href="{{ route('admin.dashboard', ['period' => 'month']) }}" class="{{ $period == 'month' ? 'font-bold text-brand' : 'text-gray-500 hover:text-brand' }}">เดือนนี้</a>
                    <a href="{{ route('admin.dashboard', ['period' => 'year']) }}" class="{{ $period == 'year' ? 'font-bold text-brand' : 'text-gray-500 hover:text-brand' }}">ปีนี้</a>
                </div>
            </div>
        </div>
        <table class="w-full text-left">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-4">#</th>
                    <th class="p-4">ลูกค้า</th>
                    <th class="p-4">วันที่</th>
                    <th class="p-4">สถานะ</th>
                    <th class="p-4 text-right">ยอดรวม</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-4">{{ $orders->firstItem() + $loop->index }}</td>
                        <td class="p-4">{{ $order->user->name }}</td>
                        <td class="p-4">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="p-4">
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                @switch($order->status)
                                    @case('pending') bg-yellow-100 text-yellow-800 @break
                                    @case('processing') bg-blue-100 text-blue-800 @break
                                    @case('shipped') bg-green-100 text-green-800 @break
                                    @case('delivered') bg-gray-200 text-gray-800 @break
                                    @case('cancelled') bg-red-100 text-red-800 @break
                                    @default bg-gray-100 text-gray-800
                                @endswitch
                            ">
                                @switch($order->status)
                                    @case('pending') รอดำเนินการ @break
                                    @case('processing') กำลังเตรียมจัดส่ง @break
                                    @case('shipped') จัดส่งแล้ว @break
                                    @case('delivered') จัดส่งสำเร็จ @break
                                    @case('cancelled') ยกเลิก @break
                                    @default {{ ucfirst($order->status) }}
                                @endswitch
                            </span>
                        </td>
                        <td class="p-4 text-right font-semibold">฿{{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-500">ไม่พบคำสั่งซื้อในช่วงเวลานี้</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($orders->hasPages())
            <div class="p-4 border-t">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

@endsection