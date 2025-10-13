@extends('admin.layout')
@section('title', 'จัดการคำสั่งซื้อ')

@section('admin_content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">จัดการคำสั่งซื้อ</h1>
</div>

{{-- Filter by Status --}}
<div class="mb-4 flex items-center gap-2 text-sm">
    <a href="{{ route('admin.orders.index') }}" class="{{ !request('status') ? 'font-bold text-brand' : '' }}">ทั้งหมด</a>
    <span class="text-gray-300">|</span>
    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="{{ request('status') == 'pending' ? 'font-bold text-brand' : '' }}">รอดำเนินการ</a>
    <span class="text-gray-300">|</span>
    <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="{{ request('status') == 'processing' ? 'font-bold text-brand' : '' }}">กำลังเตรียม</a>
    <span class="text-gray-300">|</span>
    <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="{{ request('status') == 'shipped' ? 'font-bold text-brand' : '' }}">จัดส่งแล้ว</a>
    <span class="text-gray-300">|</span>
    <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="{{ request('status') == 'delivered' ? 'font-bold text-brand' : '' }}">สำเร็จ</a>
     <span class="text-gray-300">|</span>
    <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="{{ request('status') == 'cancelled' ? 'font-bold text-brand' : '' }}">ยกเลิก</a>
</div>


<div class="bg-white border rounded-lg shadow-sm">
    <table class="w-full text-left">
        <thead class="border-b bg-gray-50">
            <tr>
                <th class="p-4">#</th>
                <th class="p-4">ลูกค้า</th>
                <th class="p-4">วันที่</th>
                <th class="p-4">สถานะ</th>
                <th class="p-4 text-right">ยอดรวม</th>
                <th class="p-4"></th>
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
                    <td class="p-4 text-right">
                        <a href="{{ route('admin.orders.show', $order) }}" class="text-brand hover:underline">
                            ดูรายละเอียด
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-6 text-center text-gray-500">
                        ไม่มีคำสั่งซื้อ
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
@endsection
