@extends('admin.layout')
@section('title', 'เพิ่มหมวดหมู่ใหม่')

@section('admin_content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">เพิ่มหมวดหมู่ใหม่</h1>

    <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-lg p-8">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="mb-6">
                <label for="name" class="block text-lg font-semibold text-gray-700 mb-2">ชื่อหมวดหมู่</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required maxlength="100"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow shadow-sm @error('name') border-red-500 @enderror"
                       placeholder="เช่น เสื้อผ้า, เครื่องประดับ">
                @error('name')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-4 mt-8 border-t border-gray-200 pt-6">
                <a href="{{ route('admin.categories.index') }}" class="px-6 py-2 rounded-lg text-gray-700 font-semibold hover:bg-gray-100 transition-colors">
                    ยกเลิก
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-md transition-transform transform hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                    <span>บันทึก</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
