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

                                {{-- Review Info --}}
                                @if($item->product)
                                <div class="mt-3 pt-3 border-t border-dashed">
                                    @forelse($item->product->reviews as $review)
                                        <div class="text-xs">
                                            <p class="font-semibold text-gray-700">รีวิวโดย: {{ $review->user->name }}</p>
                                            <div class="flex items-center gap-1 text-yellow-400 my-1">
                                                @for ($i = 0; $i < $review->rating; $i++)
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                                @endfor
                                            </div>
                                            <p class="text-gray-600">"{{ $review->comment }}"</p>
                                        </div>
                                    @empty
                                        <p class="text-xs text-gray-500">ยังไม่มีรีวิว</p>
                                    @endforelse
                                </div>
                                @endif
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
            <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" id="statusForm">
                @csrf
                <div class="space-y-3">
                    <select name="status" id="statusSelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand focus:border-brand">
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

                    <div id="trackingNumberGroup" class="hidden space-y-3">
                        <label for="tracking_number" class="block text-sm font-medium mb-1">เลขพัสดุ</label>
                        <input type="text" name="tracking_number" id="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" maxlength="100" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand focus:border-brand font-mono">

                        <label for="shipping_carrier" class="block text-sm font-medium mb-1">บริษัทขนส่ง</label>
                        <input type="text" name="shipping_carrier" id="shipping_carrier" value="{{ old('shipping_carrier', $order->shipping_carrier) }}" maxlength="100" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand focus:border-brand">
                    </div>

                    <button type="submit" class="w-full px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                        อัปเดตสถานะ
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white border rounded-lg shadow-sm p-4">
            <h2 class="text-lg font-semibold mb-2">ข้อมูลลูกค้าและการจัดส่ง</h2>
            <dl class="space-y-1 text-sm">
                <dt class="font-semibold">ชื่อลูกค้า:</dt>
                <dd class="ml-4">{{ $order->user->name }} ({{ $order->user->email }})</dd>
                
                <dt class="font-semibold pt-2 mt-2 border-t">ชื่อผู้รับ:</dt>
                <dd class="ml-4">{{ $order->shipping_name }}</dd>
                
                <dt class="font-semibold mt-2">ที่อยู่จัดส่ง:</dt>
                <dd class="ml-4 whitespace-pre-wrap">{{ $order->shipping_address }}</dd>
                
                <dt class="font-semibold mt-2">เบอร์โทรศัพท์:</dt>
                <dd class="ml-4">{{ $order->shipping_phone }}</dd>

                @if($order->tracking_number)
                <dt class="font-semibold pt-2 mt-2 border-t">เลขพัสดุ:</dt>
                <dd class="ml-4 font-mono text-blue-600">{{ $order->tracking_number }}</dd>
                @endif

                @if($order->shipping_carrier)
                <dt class="font-semibold mt-2">บริษัทขนส่ง:</dt>
                <dd class="ml-4">{{ $order->shipping_carrier }}</dd>
                @endif
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


    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('statusSelect');
        const trackingGroup = document.getElementById('trackingNumberGroup');
        const trackingInput = document.getElementById('tracking_number');
        const carrierInput = document.getElementById('shipping_carrier');

        function toggleTrackingInput() {
            if (statusSelect.value === 'shipped') {
                trackingGroup.classList.remove('hidden');
                trackingInput.required = true;
                carrierInput.required = true;
            } else {
                trackingGroup.classList.add('hidden');
                trackingInput.required = false;
                carrierInput.required = false;
            }
        }

        // Initial check on page load
        toggleTrackingInput();

        // Listen for changes
        statusSelect.addEventListener('change', toggleTrackingInput);
    });
</script>
@endpush

