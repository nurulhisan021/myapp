@extends('layouts.app')
@section('title', 'สินค้าที่อยากได้')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold mb-6">สินค้าที่อยากได้</h1>

    @if($products->isEmpty())
        <div class="rounded-xl border bg-white p-6 text-center text-gray-500">
            <p>รายการสินค้าที่อยากได้ของคุณยังว่างเปล่า</p>
            <a href="{{ route('products.index') }}" class="mt-4 inline-block px-5 py-2 rounded-lg bg-brand text-white hover:bg-brand-dark">เลือกซื้อสินค้า</a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach($products as $p)
                <div class="group bg-white rounded-2xl border shadow-sm overflow-hidden">
                    <a href="{{ route('products.show', $p) }}" class="block overflow-hidden">
                        <img src="{{ $p->image_url }}" alt="{{ $p->name }}" class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-500">
                    </a>
                    <div class="p-4">
                        @if($p->category)
                            <p class="text-sm text-gray-500 mb-1">{{ $p->category->name }}</p>
                        @endif
                        <a href="{{ route('products.show', $p) }}" class="font-semibold text-lg text-gray-800 line-clamp-1 hover:text-brand">{{ $p->name }}</a>
                        <p class="text-brand font-bold text-xl mt-2">฿{{ number_format($p->price, 2) }}</p>

                        <div class="mt-4 space-y-2">
                            {{-- Stock Status & Add to Cart --}}
                            @if ($p->stock > 0)
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $p->id }}">
                                    <input type="hidden" name="qty" value="1">
                                    <button class="w-full px-4 py-2 rounded-lg bg-brand text-white font-semibold hover:bg-brand-dark transition-colors text-sm">
                                        ใส่ตะกร้า
                                    </button>
                                </form>
                            @else
                                <p class="w-full text-center px-4 py-2 rounded-lg bg-gray-200 text-gray-500 text-sm font-semibold">สินค้าหมด</p>
                            @endif

                            {{-- Remove from Wishlist --}}
                            <form action="{{ route('wishlist.toggle', $p->id) }}" method="POST">
                                @csrf
                                <button class="w-full px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-100 text-sm">
                                    นำออกจาก Wishlist
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
