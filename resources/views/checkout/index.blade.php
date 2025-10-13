@extends('layouts.app')
@section('title', 'ยืนยันการสั่งซื้อ')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">ยืนยันการสั่งซื้อ</h1>

    {{-- Bank Accounts Display --}}
    <div class="bg-white border rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">1. โอนเงินเพื่อชำระค่าสินค้า</h2>
        <p class="text-sm text-gray-600 mb-4">กรุณาโอนเงินมายังบัญชีด้านล่างนี้ และแนบสลิปการโอนในขั้นตอนถัดไป</p>
        @if($bankAccount)
            <div class="border rounded-lg p-4 bg-gray-50">
                <div>
                    <p class="font-semibold">{{ $bankAccount->bank_name }}</p>
                    <p class="text-gray-800">เลขที่บัญชี: <span class="font-mono text-lg">{{ $bankAccount->account_number }}</span></p>
                    <p class="text-gray-800">ชื่อบัญชี: {{ $bankAccount->account_name }}</p>
                </div>
                @if($bankAccount->qr_code_url)
                <div class="mt-4 pt-4 border-t text-center">
                    <img src="{{ $bankAccount->qr_code_url }}" alt="QR Code" class="w-70 h-80 mx-auto rounded-lg border p-1 bg-white">
                    <p class="text-xs text-gray-500 mt-1">สแกน QR เพื่อชำระเงิน</p>
                </div>
                @endif
            </div>
        @else
            <p class="text-center text-gray-500 py-4">- ยังไม่มีข้อมูลบัญชีธนาคาร (กรุณาตั้งค่าในหน้าแอดมิน) -</p>
        @endif
    </div>

    @if (session('error'))
        <div class="mb-4 p-4 rounded-md bg-red-100 text-red-800">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 rounded bg-red-50 border border-red-200 text-red-700 p-3">
            <p><strong>กรุณาแก้ไขข้อผิดพลาดต่อไปนี้:</strong></p>
            <ul class="list-disc list-inside mt-2">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        {{-- Shipping Form --}}
        <div class="bg-white border rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold mb-4">2. กรอกข้อมูลจัดส่งและแนบสลิป</h2>
            <form action="{{ route('checkout.placeOrder') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
                @csrf
                <div>
                    <label for="shipping_name" class="block text-sm font-medium text-gray-700 mb-1">ชื่อผู้รับ</label>
                    <input type="text" name="shipping_name" id="shipping_name" value="{{ old('shipping_name', auth()->user()->name) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand focus:border-brand">
                </div>
                <div>
                    <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-1">ที่อยู่สำหรับจัดส่ง</label>
                    <textarea name="shipping_address" id="shipping_address" rows="3" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand focus:border-brand">{{ old('shipping_address') }}</textarea>
                </div>
                <div>
                    <label for="shipping_phone" class="block text-sm font-medium text-gray-700 mb-1">เบอร์โทรศัพท์</label>
                    <input type="text" name="shipping_phone" id="shipping_phone" value="{{ old('shipping_phone') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand focus:border-brand">
                </div>
                <div>
                    <label for="payment_slip" class="block text-sm font-medium text-gray-700 mb-1">แนบสลิปการโอนเงิน</label>
                    <input type="file" name="payment_slip" id="payment_slip" required accept="image/*"
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-brand hover:file:bg-pink-100">
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full px-6 py-3 rounded-lg bg-brand text-white font-bold hover:bg-brand-dark">
                        ยืนยันการสั่งซื้อ
                    </button>
                </div>
            </form>
        </div>

        {{-- Cart Summary --}}
        <div class="bg-white border rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold mb-4">สรุปรายการสั่งซื้อ</h2>
            <div class="space-y-4">
                @foreach($products as $product)
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-16 h-16 rounded object-cover">
                            <div>
                                <p class="font-semibold">{{ $product->name }}</p>
                                <p class="text-sm text-gray-600">จำนวน: {{ $cart[$product->id]['qty'] }}</p>
                            </div>
                        </div>
                        <p class="font-semibold">฿{{ number_format($product->price * $cart[$product->id]['qty'], 2) }}</p>
                    </div>
                @endforeach
            </div>
            <div class="mt-6 pt-4 border-t">
                <div class="flex justify-between items-center font-bold text-lg">
                    <p>ยอดรวมทั้งสิ้น</p>
                    <p>฿{{ number_format($total, 2) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
