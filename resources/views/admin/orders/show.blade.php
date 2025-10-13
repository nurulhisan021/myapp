@extends('admin.layout')
@section('title', 'รายละเอียดคำสั่งซื้อ #' . $order->id)

@section('admin_content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">รายละเอียดคำสั่งซื้อ #{{ $order->id }}</h1>
</div>

@if (session('success'))
    <div class="mb-4 p-4 rounded-md bg-green-100 text-green-800">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    {{-- Order Details & Shipping --}}
    <div class="md:col-span-2 space-y-6">
        {{-- Items List --}}
        <div class="bg-white border rounded-lg shadow-sm">
            <h2 class="text-lg font-semibold p-4 border-b">รายการสินค้า</h2>
            <div class="divide-y">
                @foreach($order->items as $item)
                    <div class="p-4 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <img src="{{ $item->product->image_url ?? asset('images/product-placeholder.png') }}" alt="{{ $item->product->name ?? 'N/A' }}" class="w-16 h-16 rounded object-cover">
                            <div>
                                <p class="font-semibold">{{ $item->product->name ?? '[สินค้าถูกลบ]' }}</p>
                                <p class="text-sm text-gray-600">
                                    {{ $item->quantity }} x ฿{{ number_format($item->price, 2) }}
                                </p>
                            </div>
                        </div>
                        <p class="font-semibold">฿{{ number_format($item->price * $item->quantity, 2) }}</p>
                    </div>
                @endforeach
            </div>
            <div class="p-4 bg-gray-50 border-t">
                <div class="flex justify-between items-center font-bold">
                    <span>ยอดรวมทั้งสิ้น</span>
                    <span>฿{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Status & Info --}}
    <div class="space-y-6">
        {{-- Update Status Form --}}
        <div class="bg-white border rounded-lg shadow-sm p-4">
            <h2 class="text-lg font-semibold mb-3">อัปเดตสถานะ</h2>
            <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                @csrf
                <div class="flex items-center gap-2">
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand focus:border-brand">
                        @php
                            $statuses = [
                                'pending' => 'รอดำเนินการ',
                                'processing' => 'กำลังเตรียมจัดส่ง',
                                'shipped' => 'จัดส่งแล้ว',
                                'delivered' => 'จัดส่งสำเร็จ',
                                'cancelled' => 'ยกเลิก',
                            ];
                        @endphp
                        @foreach($statuses as $status => $label)
                            <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                        อัปเดต
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white border rounded-lg shadow-sm p-4">
            <h2 class="text-lg font-semibold mb-2">ข้อมูลลูกค้า</h2>
            <dl class="space-y-1 text-sm">
                <dt class="font-semibold">ชื่อ:</dt>
                <dd class="ml-4">{{ $order->user->name }}</dd>
                <dt class="font-semibold mt-2">อีเมล:</dt>
                <dd class="ml-4">{{ $order->user->email }}</dd>
            </dl>
        </div>

        <div class="bg-white border rounded-lg shadow-sm p-4">
            <h2 class="text-lg font-semibold mb-2">สลิปการโอนเงิน</h2>
            @if($order->payment_slip_url)
                <a href="{{ $order->payment_slip_url }}" target="_blank">
                    <img src="{{ $order->payment_slip_url }}" alt="Payment Slip" class="w-full rounded-lg border hover:opacity-80">
                </a>
            @else
                <p class="text-center text-gray-500 py-4">- ไม่ได้แนบสลิป -</p>
            @endif
        </div>

        <div class="bg-white border rounded-lg shadow-sm p-4">
            <h2 class="text-lg font-semibold mb-2">ข้อมูลการจัดส่ง</h2>
            <dl class="space-y-1 text-sm">
                <dt class="font-semibold">ชื่อผู้รับ:</dt>
                <dd class="ml-4">{{ $order->shipping_name }}</dd>
                <dt class="font-semibold mt-2">ที่อยู่:</dt>
                <dd class="ml-4 whitespace-pre-wrap">{{ $order->shipping_address }}</dd>
                <dt class="font-semibold mt-2">เบอร์โทรศัพท์:</dt>
                <dd class="ml-4">{{ $order->shipping_phone }}</dd>
            </dl>
        </div>
    </div>
</div>
@endsection
