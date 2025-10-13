@extends('admin.layout')
@section('title', 'จัดการหมวดหมู่')

@section('admin_content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">จัดการหมวดหมู่</h1>
    <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">
        + เพิ่มหมวดหมู่ใหม่
    </a>
</div>

{{-- Display success/error messages --}}
@if (session('success'))
    <div class="mb-4 p-4 rounded-md bg-green-100 text-green-800">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="mb-4 p-4 rounded-md bg-red-100 text-red-800">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white border rounded-lg shadow-sm">
    <table class="w-full text-left">
        <thead class="border-b">
            <tr>
                <th class="p-4">#</th>
                <th class="p-4">ชื่อหมวดหมู่</th>
                <th class="p-4">จำนวนสินค้า</th>
                <th class="p-4">จัดการ</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $category)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4">{{ $categories->firstItem() + $loop->index }}</td>
                    <td class="p-4 font-semibold">{{ $category->name }}</td>
                    <td class="p-4">{{ $category->products()->count() }}</td>
                    <td class="p-4 flex gap-2">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-600 hover:underline">แก้ไข</a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบหมวดหมู่นี้?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">ลบ</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-4 text-center text-gray-500">
                        ยังไม่มีหมวดหมู่
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($categories->hasPages())
        <div class="p-4 border-t">
            {{ $categories->links() }}
        </div>
    @endif
</div>
@endsection
