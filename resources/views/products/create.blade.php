@extends('admin.layout')
@section('title','เพิ่มสินค้า')

@section('admin_content')
<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">เพิ่มสินค้าใหม่</h1>
            <p class="text-sm text-gray-500">กรอกรายละเอียดสินค้าด้านล่าง</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 rounded-lg border bg-white hover:bg-gray-50 text-sm font-medium">ยกเลิก</a>
            <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm font-medium">บันทึกสินค้า</button>
        </div>
    </div>

    {{-- Display Errors --}}
    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 p-4 text-sm">
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
            <div class="bg-white border rounded-lg shadow-sm p-6">
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">ชื่อสินค้า</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand focus:border-brand">
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">รายละเอียดสินค้า</label>
                        <textarea name="description" id="description" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand focus:border-brand">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="space-y-6">
            {{-- Image Card --}}
            <div class="bg-white border rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium mb-4">รูปสินค้า</h3>
                <div class="space-y-2">
                    <img id="preview" src="{{ asset('images/product-placeholder.png') }}" class="w-full h-48 rounded-lg object-cover border" alt="Image preview">
                    <label for="image-upload" class="w-full text-center cursor-pointer block px-3 py-2 rounded-lg border bg-white hover:bg-gray-50 text-sm font-medium">เลือกรูปภาพ</label>
                    <input type="file" name="image" id="image-upload" accept="image/*" class="hidden">
                </div>
            </div>

            {{-- Attributes Card --}}
            <div class="bg-white border rounded-lg shadow-sm p-6">
                <div class="space-y-4">
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">หมวดหมู่</label>
                        <select name="category_id" id="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand focus:border-brand">
                            <option value="">-- ไม่มี --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">ราคา</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">฿</span>
                            <input type="number" step="0.01" min="0" name="price" id="price" value="{{ old('price') }}" required class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-brand focus:border-brand">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
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
