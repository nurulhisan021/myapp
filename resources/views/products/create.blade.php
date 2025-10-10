@extends('layouts.app')
@section('title','เพิ่มสินค้า')

@section('content')
<div class="max-w-3xl mx-auto">
  <h1 class="text-2xl font-semibold mb-4">เพิ่มสินค้า</h1>

  @if($errors->any())
    <div class="mb-4 rounded bg-red-50 border border-red-200 text-red-700 p-3">
      <ul class="list-disc list-inside">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- ชี้ไปที่ route ฝั่งแอดมิน --}}
  <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
    @csrf

    <div>
      <label class="block text-sm mb-1">ชื่อสินค้า</label>
      <input type="text" name="name" value="{{ old('name') }}" required
             class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">
      @error('name') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm mb-1">หมวดหมู่ (ถ้ามี)</label>
        <input type="text" name="category" value="{{ old('category') }}"
               class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">
        @error('category') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
      </div>
      <div>
        <label class="block text-sm mb-1">ราคา</label>
        <input type="number" step="0.01" min="0" name="price" value="{{ old('price') }}" required
               class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">
        @error('price') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
      </div>
    </div>

    <div>
      <label class="block text-sm mb-1">รายละเอียด</label>
      <textarea name="description" rows="4"
                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
      @error('description') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
    </div>

    <div>
      <label class="block text-sm mb-1">รูปสินค้า</label>
      <div class="flex items-center gap-4">
        <img id="preview" src="{{ asset('images/product-placeholder.png') }}"
             class="w-24 h-24 rounded object-cover border" alt="preview">
        <input type="file" name="image" accept="image/*"
               class="rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">
      </div>
      @error('image') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="flex items-center gap-2">
      <button class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">บันทึก</button>
      <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl border hover:bg-gray-50">ยกเลิก</a>
    </div>
  </form>
</div>

{{-- พรีวิวรูป --}}
<script>
  const input = document.querySelector('input[name="image"]');
  const img   = document.getElementById('preview');
  input?.addEventListener('change', () => {
    const [f] = input.files || [];
    if (f) img.src = URL.createObjectURL(f);
  });
</script>
@endsection
