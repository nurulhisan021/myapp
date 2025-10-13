@extends('admin.layout')
@section('title', 'เพิ่มหมวดหมู่ใหม่')

@section('admin_content')
<h1 class="text-2xl font-bold mb-6">เพิ่มหมวดหมู่ใหม่</h1>

<div class="max-w-lg mx-auto bg-white border rounded-lg shadow-sm p-6">
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">ชื่อหมวดหมู่</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required maxlength="100"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand focus:border-brand @error('name') border-red-500 @enderror">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-4 mt-6">
            <button type="submit" class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">
                บันทึก
            </button>
            <a href="{{ route('admin.categories.index') }}" class="text-gray-600 hover:underline">
                ยกเลิก
            </a>
        </div>
    </form>
</div>
@endsection
