@extends('admin.layout')
@section('title','เพิ่มสินค้า')

@section('admin_content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">เพิ่มสินค้าใหม่</h1>
                <p class="text-gray-600">กรอกรายละเอียดสินค้าด้านล่าง</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-700 font-semibold hover:bg-gray-100 transition-colors shadow-sm">ยกเลิก</a>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-md transition-transform transform hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                    <span>บันทึกสินค้า</span>
                </button>
            </div>
        </div>

        {{-- Display Errors --}}
        @if($errors->any())
            <div class="mb-6 rounded-lg bg-red-50 border border-red-200 text-red-700 p-4 text-sm shadow-sm">
              <strong class="font-bold">เกิดข้อผิดพลาด!</strong>
              <ul class="list-disc list-inside mt-1">
                @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
              </ul>
            </div>
        @endif

        {{-- Form Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Main Details Card --}}
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">ข้อมูลหลัก</h3>
                    <div class="space-y-5">
                        <div>
                            <label for="name" class="block text-md font-medium text-gray-700 mb-2">ชื่อสินค้า</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-shadow shadow-sm @error('name') border-red-500 @enderror"
                                   placeholder="ชื่อสินค้าของคุณ">
                            @error('name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="description" class="block text-md font-medium text-gray-700 mb-2">รายละเอียดสินค้า</label>
                            <textarea name="description" id="description" rows="8"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-shadow shadow-sm @error('description') border-red-500 @enderror"
                                      placeholder="คำอธิบายสินค้าโดยละเอียด">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column --}}
            <div class="space-y-6">
                {{-- Image Card --}}
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">รูปภาพสินค้า</h3>
                    <div class="space-y-4">
                        <img id="preview" src="{{ asset('images/product-placeholder.png') }}" class="w-full h-48 rounded-lg object-cover border border-gray-200 shadow-sm" alt="Image preview">
                        <label for="image-upload" class="w-full text-center cursor-pointer block px-4 py-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-100 text-gray-700 font-medium transition-colors shadow-sm">เลือกรูปภาพ</label>
                        <input type="file" name="image" id="image-upload" accept="image/*" class="hidden">
                        @error('image')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Attributes Card --}}
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">คุณสมบัติ</h3>
                    <div class="space-y-5">
                        <div>
                            <label for="category_id" class="block text-md font-medium text-gray-700 mb-2">หมวดหมู่</label>
                            <select name="category_id" id="category_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-shadow shadow-sm @error('category_id') border-red-500 @enderror">
                                <option value="">-- เลือกหมวดหมู่ --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="price" class="block text-md font-medium text-gray-700 mb-2">ราคา</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500">฿</span>
                                <input type="number" step="0.01" min="0" name="price" id="price" value="{{ old('price') }}" required
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-shadow shadow-sm @error('price') border-red-500 @enderror"
                                       placeholder="0.00">
                                @error('price')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label for="stock" class="block text-md font-medium text-gray-700 mb-2">จำนวนสต็อก</label>
                            <input type="number" min="0" name="stock" id="stock" value="{{ old('stock', 0) }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-shadow shadow-sm @error('stock') border-red-500 @enderror"
                                   placeholder="0">
                            @error('stock')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
  const input = document.getElementById('image-upload');
  const img = document.getElementById('preview');
  input?.addEventListener('change', () => {
    const [file] = input.files;
    if (file) {
      img.src = URL.createObjectURL(file);
    }
  });
</script>
@endpush
