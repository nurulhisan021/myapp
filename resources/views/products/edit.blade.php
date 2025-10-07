@extends('layouts.app')
@section('title','แก้ไขสินค้า')

@section('content')
<div class="max-w-xl mx-auto">
  <h1 class="text-xl font-semibold mb-4">แก้ไข: {{ $product->name }}</h1>

  @if($errors->any())
    <div class="mb-4 rounded bg-red-50 border border-red-200 text-red-700 p-3">
      <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  <form action="{{ route('products.update',$product) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
    @csrf @method('PUT')

    <div>
      <label class="block text-sm mb-1">ชื่อสินค้า</label>
      <input type="text" name="name" value="{{ old('name',$product->name) }}" required
             class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">
    </div>

    <div>
      <label class="block text-sm mb-1">ราคา (บาท)</label>
      <input type="number" name="price" value="{{ old('price',$product->price) }}" step="0.01" min="0" required
             class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">
    </div>

    <div>
      <label class="block text-sm mb-1">รายละเอียด</label>
      <textarea name="description" rows="4"
                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">{{ old('description',$product->description) }}</textarea>
    </div>

    <div>
      <label class="block text-sm mb-1">รูปสินค้า</label>
      <div class="flex items-center gap-4">
        <img src="{{ $product->image_url }}" id="preview-edit"
             class="w-24 h-24 rounded-lg object-cover border" alt="product">
        <input type="file" name="image" accept="image/*"
               class="rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500" id="input-edit">
        <label class="inline-flex items-center gap-2">
          <input type="checkbox" name="remove_image" value="1">
          <span>ลบรูปปัจจุบัน</span>
        </label>
      </div>
      @error('image')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
    </div>

    <div class="flex items-center gap-2">
      <button class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">อัปเดต</button>
      <a href="{{ route('products.index') }}" class="px-4 py-2 rounded-xl border hover:bg-gray-50">ย้อนกลับ</a>
    </div>
  </form>
</div>

<script>
const ie = document.getElementById('input-edit');
const pe = document.getElementById('preview-edit');
ie?.addEventListener('change', () => {
  const [f] = ie.files || [];
  if (f) pe.src = URL.createObjectURL(f);
});
</script>
@endsection

