@extends('layouts.app')
@section('title', 'ประวัติการสั่งซื้อ')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6">
    <h1 class="text-2xl font-bold mb-6">ประวัติการสั่งซื้อ</h1>

    @if (session('success'))
        <div class="mb-4 p-4 rounded-md bg-green-100 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border rounded-lg shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[640px]">
            <thead class="border-b bg-gray-50">
                <tr>
                    <th class="p-4">คำสั่งซื้อ</th>
                    <th class="p-4">วันที่</th>
                    <th class="p-4">สถานะ</th>
                    <th class="p-4">จำนวน</th>
                    <th class="p-4 text-right">ยอดรวม</th>
                    <th class="p-4"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-4 font-semibold">#{{ $order->id }}</td>
                        <td class="p-4">{{ $order->created_at->format('d/m/') . ($order->created_at->format('Y') + 543) . $order->created_at->format(' H:i') }}</td>
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
                        <td class="p-4">{{ $order->items_count }} รายการ</td>
                        <td class="p-4 text-right font-semibold">฿{{ number_format($order->total_amount, 2) }}</td>
                        <td class="p-4 text-right space-x-2 whitespace-nowrap">
                            <a href="{{ route('account.orders.show', $order) }}" class="text-brand hover:underline text-sm">ดูรายละเอียด</a>
                            @if($order->status == 'pending')
                            <form action="{{ route('account.orders.cancel', $order) }}" method="POST" class="inline" onsubmit="return confirm('คุณต้องการยกเลิกคำสั่งซื้อนี้ใช่หรือไม่?');">
                                @csrf
                                <button class="text-red-500 hover:underline text-sm">ยกเลิก</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-gray-500">
                            คุณยังไม่มีคำสั่งซื้อ
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>        @if($orders->hasPages())
            <div class="p-4 border-t">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
