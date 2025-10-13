@extends('admin.layout')
@section('title', 'จัดการสินค้า')

@section('admin_content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">จัดการสินค้า</h1>
    <a href="{{ route('admin.products.create') }}" class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">
        + เพิ่มสินค้าใหม่
    </a>
</div>

@if (session('success'))
    <div class="mb-4 p-4 rounded-md bg-green-100 text-green-800">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white border rounded-lg shadow-sm">
    <table class="w-full text-left">
        <thead class="border-b bg-gray-50">
            <tr>
                <th class="p-4">#</th>
                <th class="p-4">สินค้า</th>
                <th class="p-4">หมวดหมู่</th>
                <th class="p-4">ราคา</th>
                <th class="p-4">วันที่สร้าง</th>
                <th class="p-4">จัดการ</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4">{{ $products->firstItem() + $loop->index }}</td>
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $product->image_url }}" class="w-12 h-12 rounded object-cover border" alt="{{ $product->name }}">
                            <span class="font-semibold">{{ $product->name }}</span>
                        </div>
                    </td>
                    <td class="p-4">{{ $product->category->name ?? '-' }}</td>
                    <td class="p-4">฿{{ number_format($product->price, 2) }}</td>
                    <td class="p-4">{{ $product->created_at->format('d/m/Y') }}</td>
                    <td class="p-4 flex gap-2">
                        <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-600 hover:underline">แก้ไข</a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบสินค้านี้?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">ลบ</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-6 text-center text-gray-500">
                        ยังไม่มีสินค้า
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($products->hasPages())
        <div class="p-4 border-t">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
