@extends('layouts.app')
@section('title','แก้ไขสินค้า')

@section('content')
<div class="max-w-3xl mx-auto">
  <h1 class="text-2xl font-semibold mb-4">แก้ไขสินค้า #{{ $product->id }}</h1>

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
  <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
    @csrf
    @method('PUT')

    <div>
      <label class="block text-sm mb-1">ชื่อสินค้า</label>
      <input type="text" name="name" value="{{ old('name', $product->name) }}" required
             class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">
      @error('name') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm mb-1">หมวดหมู่</label>
        <input type="text" name="category" value="{{ old('category', $product->category) }}"
               class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">
        @error('category') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
      </div>
      <div>
        <label class="block text-sm mb-1">ราคา</label>
        <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $product->price) }}" required
               class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">
        @error('price') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
      </div>
    </div>

    <div>
      <label class="block text-sm mb-1">รายละเอียด</label>
      <textarea name="description" rows="4"
                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">{{ old('description', $product->description) }}</textarea>
      @error('description') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="space-y-2">
      <label class="block text-sm">รูปสินค้า</label>
      <div class="flex items-center gap-4">
        <img id="preview-edit" src="{{ $product->image_url }}"
             class="w-24 h-24 rounded object-cover border" alt="current">
        <input type="file" name="image" accept="image/*"
               class="rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">
      </div>

      <label class="inline-flex items-center gap-2">
        <input type="checkbox" name="remove_image" value="1">
        <span>ลบรูปปัจจุบัน</span>
      </label>

      @error('image') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
    </div>

    <div class="flex items-center gap-2">
      <button class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">อัปเดต</button>
      <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl border hover:bg-gray-50">ยกเลิก</a>
    </div>
  </form>
</div>

<script>
  const inputE = document.querySelector('input[name="image"]');
  const imgE   = document.getElementById('preview-edit');
  inputE?.addEventListener('change', () => {
    const [f] = inputE.files || [];
    if (f) imgE.src = URL.createObjectURL(f);
  });
</script>
@endsection
