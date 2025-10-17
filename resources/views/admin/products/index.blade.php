@extends('admin.layout')
@section('title', 'จัดการสินค้า')

@section('admin_content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">จัดการสินค้า</h1>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-transform transform hover:scale-105">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
            <span>เพิ่มสินค้าใหม่</span>
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-col sm:flex-row items-center gap-4">
            <div class="flex-grow w-full sm:w-auto">
                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">กรองตามหมวดหมู่:</label>
                <select name="category" id="category" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="">ทุกหมวดหมู่</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $selectedCategory == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="mt-auto px-5 py-2.5 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors w-full sm:w-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" /></svg>
                กรอง
            </button>
        </form>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-100 text-green-800 border border-green-200 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Products Table --}}
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-100 border-b border-gray-200">
                    <tr>
                        <th class="p-5 font-semibold text-gray-600">#</th>
                        <th class="p-5 font-semibold text-gray-600">สินค้า</th>
                        <th class="p-5 font-semibold text-gray-600">หมวดหมู่</th>
                        <th class="p-5 font-semibold text-gray-600">ราคา</th>
                        <th class="p-5 font-semibold text-gray-600">สต็อก</th>
                        <th class="p-5 font-semibold text-gray-600">วันที่สร้าง</th>
                        <th class="p-5 font-semibold text-gray-600 text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                            <td class="p-5 text-gray-500">{{ $products->firstItem() + $loop->index }}</td>
                            <td class="p-5">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $product->image_url }}" class="w-14 h-14 rounded-lg object-cover border border-gray-200 shadow-sm" alt="{{ $product->name }}">
                                    <span class="font-semibold text-gray-800">{{ $product->name }}</span>
                                </div>
                            </td>
                            <td class="p-5 text-gray-600">{{ $product->category->name ?? '-' }}</td>
                            <td class="p-5 text-gray-600">฿{{ number_format($product->price, 2) }}</td>
                            <td class="p-5 text-gray-600">{{ $product->stock }}</td>
                            <td class="p-5 text-gray-600">{{ $product->created_at->format('d/m/Y') }}</td>
                            <td class="p-5">
                                <div class="flex justify-center items-center gap-3">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-600 hover:text-blue-800 transition-colors p-2 rounded-full hover:bg-blue-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบสินค้านี้?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition-colors p-2 rounded-full hover:bg-red-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                                    <p class="font-semibold">ยังไม่มีสินค้า</p>
                                    <p class="text-sm">เริ่มสร้างสินค้าใหม่เพื่อแสดงในร้านค้าของคุณ</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($products->hasPages())
                <div class="p-5 border-t border-gray-200 bg-gray-50">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
