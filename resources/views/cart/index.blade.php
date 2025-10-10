@extends('layouts.app')
@section('title','ตะกร้าสินค้า')

@section('content')
<h1 class="text-2xl font-semibold mb-4">ตะกร้าสินค้า</h1>

@if(empty($cart) || collect($cart)->isEmpty())
  <div class="rounded-xl border bg-white p-6 text-gray-500">ตะกร้ายังว่างเปล่า</div>
@else
  <div class="rounded-2xl border bg-white overflow-hidden">
    <table class="w-full text-left">
      <thead class="bg-gray-50 text-gray-600 text-sm">
        <tr>
          <th class="px-4 py-3">สินค้า</th>
          <th class="px-4 py-3 w-32">ราคา</th>
          <th class="px-4 py-3 w-40">จำนวน</th>
          <th class="px-4 py-3 w-32">รวม</th>
          <th class="px-4 py-3 w-24"></th>
        </tr>
      </thead>
      <tbody class="divide-y">
        @php $sum = 0; @endphp
        @foreach($cart as $item)
          @php $total = $item['qty'] * $item['price']; $sum += $total; @endphp
          <tr class="align-middle">
            <td class="px-4 py-3">
              <div class="flex items-center gap-3">
                <img src="{{ $item['image_url'] ?? asset('images/product-placeholder.png') }}" class="w-12 h-12 rounded object-cover">
                <div class="font-medium">{{ $item['name'] }}</div>
              </div>
            </td>
            <td class="px-4 py-3">{{ number_format($item['price'],2) }}</td>
            <td class="px-4 py-3">
              <form action="{{ route('cart.update',$item['id']) }}" method="POST" class="flex items-center gap-2">
                @csrf
                <input type="number" name="qty" value="{{ $item['qty'] }}" min="1"
                       class="w-20 rounded-lg border-gray-300 text-center">
                <button class="px-3 py-1.5 rounded-lg border hover:bg-gray-50">อัปเดต</button>
              </form>
            </td>
            <td class="px-4 py-3 font-semibold">{{ number_format($total,2) }}</td>
            <td class="px-4 py-3">
              <form action="{{ route('cart.remove',$item['id']) }}" method="POST"
                    onsubmit="return confirm('เอาออกจากตะกร้า?')">
                @csrf @method('DELETE')
                <button class="px-3 py-1.5 rounded-lg bg-red-600 text-white hover:bg-red-700">ลบ</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="mt-6 flex flex-wrap items-center justify-between gap-3">
    <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('ลบทั้งหมด?')">
      @csrf @method('DELETE')
      <button class="px-4 py-2 rounded-lg border hover:bg-gray-50">ล้างตะกร้า</button>
    </form>
    <div class="text-right">
      <div class="text-gray-600">ยอดรวม</div>
      <div class="text-2xl font-bold text-brand">{{ number_format($sum,2) }} บาท</div>
    </div>
  </div>
@endif
@endsection
