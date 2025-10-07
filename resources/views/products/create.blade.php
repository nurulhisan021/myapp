@extends('layouts.app')
@section('title','เพิ่มสินค้า')

@section('content')
<div class="max-w-xl mx-auto">
  <h1 class="text-xl font-semibold mb-4">เพิ่มสินค้า</h1>

  @if($errors->any())
    <div class="mb-4 rounded bg-red-50 border border-red-200 text-red-700 p-3">
      <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
    @csrf

    <div>
      <label class="block text-sm mb-1">ชื่อสินค้า</label>
      <input type="text" name="name" value="{{ old('name') }}" required
             class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">
    </div>

    <div>
      <label class="block text-sm mb-1">ราคา (บาท)</label>
      <input type="number" name="price" value="{{ old('price') }}" step="0.01" min="0" required
             class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">
    </div>

    <div>
      <label class="block text-sm mb-1">รายละเอียด</label>
      <textarea name="description" rows="4"
                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
    </div>

    <div>
      <label class="block text-sm mb-1">รูปสินค้า</label>
      <div class="flex items-center gap-4">
        <img id="preview-create" class="w-24 h-24 rounded-lg object-cover border hidden" alt="preview">
        <input type="file" name="image" accept="image/*"
               class="rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500" id="input-create">
        <button type="button" id="clear-create" class="px-3 py-2 rounded-lg border hover:bg-gray-50 hidden">ล้างรูป</button>
      </div>
      @error('image')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
    </div>

    <div class="flex items-center gap-2">
      <button class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">บันทึก</button>
      <a href="{{ route('products.index') }}" class="px-4 py-2 rounded-xl border hover:bg-gray-50">ยกเลิก</a>
    </div>
  </form>
</div>

<script>
const ic = document.getElementById('input-create');
const pc = document.getElementById('preview-create');
const cc = document.getElementById('clear-create');
ic?.addEventListener('change', () => {
  const [f] = ic.files || [];
  if (f) { pc.src = URL.createObjectURL(f); pc.classList.remove('hidden'); cc.classList.remove('hidden'); }
});
cc?.addEventListener('click', () => {
  ic.value = ''; pc.src=''; pc.classList.add('hidden'); cc.classList.add('hidden');
});
</script>
@endsection
