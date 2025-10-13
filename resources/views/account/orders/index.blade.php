@extends('layouts.app')
@section('title', 'ประวัติการสั่งซื้อ')

@section('content')
<div class="max-w-5xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">ประวัติการสั่งซื้อ</h1>

    @if (session('success'))
        <div class="mb-4 p-4 rounded-md bg-green-100 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border rounded-lg shadow-sm">
        <table class="w-full text-left">
            <thead class="border-b bg-gray-50">
                <tr>
                    <th class="p-4">เลขที่สั่งซื้อ</th>
                    <th class="p-4">วันที่</th>
                    <th class="p-4">สถานะ</th>
                    <th class="p-4">จำนวนรายการ</th>
                    <th class="p-4 text-right">ยอดรวม</th>
                    <th class="p-4"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-4 font-semibold">#{{ $order->id }}</td>
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
                        <td class="p-4">{{ $order->items_count }}</td>
                        <td class="p-4 text-right font-semibold">฿{{ number_format($order->total_amount, 2) }}</td>
                        <td class="p-4 text-right">
                            <a href="{{ route('account.orders.show', $order) }}" class="text-brand hover:underline">
                                ดูรายละเอียด
                            </a>
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
        </table>

        @if($orders->hasPages())
            <div class="p-4 border-t">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
