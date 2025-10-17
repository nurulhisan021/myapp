@extends('admin.layout')
@section('title', 'จัดการคำสั่งซื้อ')

@section('admin_content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">จัดการคำสั่งซื้อ</h1>
    </div>

    {{-- Filter by Status --}}
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <div class="flex flex-wrap items-center gap-4 text-md font-semibold">
            <a href="{{ route('admin.orders.index', ['status' => 'all']) }}" class="px-4 py-2 rounded-lg transition-colors {{ request('status') == 'all' || !request('status') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">ทั้งหมด</a>
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-lg transition-colors {{ request('status') == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'text-gray-600 hover:bg-gray-50' }}">รอดำเนินการ</a>
            <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="px-4 py-2 rounded-lg transition-colors {{ request('status') == 'processing' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">กำลังเตรียม</a>
            <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="px-4 py-2 rounded-lg transition-colors {{ request('status') == 'shipped' ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:bg-gray-50' }}">จัดส่งแล้ว</a>
            <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="px-4 py-2 rounded-lg transition-colors {{ request('status') == 'delivered' ? 'bg-gray-200 text-gray-700' : 'text-gray-600 hover:bg-gray-50' }}">สำเร็จ</a>
            <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="px-4 py-2 rounded-lg transition-colors {{ request('status') == 'cancelled' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50' }}">ยกเลิก</a>
        </div>
    </div>

    {{-- Orders Table --}}
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-100 border-b border-gray-200">
                    <tr>
                        <th class="p-5 font-semibold text-gray-600">#</th>
                        <th class="p-5 font-semibold text-gray-600">ลูกค้า</th>
                        <th class="p-5 font-semibold text-gray-600">วันที่</th>
                        <th class="p-5 font-semibold text-gray-600">สถานะ</th>
                        <th class="p-5 font-semibold text-gray-600 text-right">ยอดรวม</th>
                        <th class="p-5 font-semibold text-gray-600 text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                            <td class="p-5 text-gray-500">{{ $orders->firstItem() + $loop->index }}</td>
                            <td class="p-5 font-semibold text-gray-800">{{ $order->user->name }}</td>
                            <td class="p-5 text-gray-600">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-5">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
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
                            <td class="p-5 text-right font-bold text-gray-800">฿{{ number_format($order->total_amount, 2) }}</td>
                            <td class="p-5 text-center">
                                <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 transition-colors p-2 rounded-full hover:bg-blue-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>
                                    <span>ดูรายละเอียด</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                                    <p class="font-semibold">ไม่มีคำสั่งซื้อ</p>
                                    <p class="text-sm">ยังไม่มีคำสั่งซื้อในสถานะนี้</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($orders->hasPages())
                <div class="p-5 border-t border-gray-200 bg-gray-50">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
