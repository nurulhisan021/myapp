@extends('layouts.app')
@section('title','สินค้า')

@section('content')
<div class="flex items-center justify-between mb-6 gap-3">
  <form method="get" class="flex gap-2">
    <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="ค้นหาชื่อสินค้า"
           class="w-64 rounded-lg border-gray-300 focus:ring-2 focus:ring-brand">
    <button class="px-3 py-2 rounded-lg bg-gray-900 text-white hover:bg-black/90">ค้นหา</button>
    @if(!empty($q))
      <a href="{{ route('products.index') }}" class="px-3 py-2 rounded-lg border hover:bg-gray-50">เคลียร์</a>
    @endif
  </form>

  @if(session('is_admin'))
    <a href="{{ route('admin.products.create') }}" class="px-4 py-2 rounded-xl bg-brand text-white hover:bg-brand-dark">
      + เพิ่มสินค้า
    </a>
  @endif
</div>

<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
  @forelse($products as $p)
    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden hover:shadow-lg transition">
      <a href="{{ route('products.show',$p) }}">
        <img src="{{ $p->image_url }}" class="w-full aspect-[4/3] object-cover" alt="{{ $p->name }}">
      </a>
      <div class="p-4">
        <a href="{{ route('products.show',$p) }}" class="font-medium line-clamp-1">{{ $p->name }}</a>
        <p class="text-brand font-semibold mt-1">{{ number_format($p->price,2) }} บาท</p>
        <p class="text-sm text-gray-500 mt-2 line-clamp-2">{{ $p->description }}</p>

        @if(session('is_admin'))
          <div class="flex items-center gap-2 mt-3">
            <a href="{{ route('admin.products.edit',$p) }}" class="px-3 py-1.5 rounded-lg border hover:bg-gray-50">แก้ไข</a>
            <form action="{{ route('admin.products.destroy',$p) }}" method="POST" onsubmit="return confirm('ยืนยันลบ?')" class="inline">
              @csrf @method('DELETE')
              <button class="px-3 py-1.5 rounded-lg bg-red-600 text-white hover:bg-red-700">ลบ</button>
            </form>
          </div>
        @else
          <form action="{{ route('cart.add') }}" method="POST" class="mt-3 flex items-center gap-2">
            @csrf
            <input type="hidden" name="product_id" value="{{ $p->id }}">
            <input type="number" name="qty" value="1" min="1" class="w-16 rounded-lg border-gray-300 text-center">
            <button class="px-3 py-1.5 rounded-lg bg-brand text-white hover:bg-brand-dark">🛒 ใส่ตะกร้า</button>
          </form>
        @endif
      </div>
    </div>
  @empty
    <p class="text-gray-500">ยังไม่มีสินค้า</p>
  @endforelse
</div>

<div class="mt-6">{{ $products->links() }}</div>
@endsection
