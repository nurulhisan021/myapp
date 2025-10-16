@extends('layouts.app')
@section('title',$product->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="grid md:grid-cols-2 gap-8">
  <div class="bg-white rounded-2xl border overflow-hidden">
    <img src="{{ $product->image_url }}" class="w-full aspect-[4/3] object-cover">
  </div>

  <div>
    <h1 class="text-2xl font-semibold">{{ $product->name }}</h1>
    <p class="mt-2 text-brand text-xl font-bold">{{ number_format($product->price,2) }} บาท</p>
    <p class="mt-4 text-gray-600 whitespace-pre-line">{{ $product->description }}</p>

    <div class="mt-4">
        @if($product->stock > 0)
            <span class="text-sm text-gray-500">มีสินค้า <span class="font-semibold">{{ $product->stock }}</span> ชิ้น</span>
        @else
            <span class="text-sm font-semibold text-red-500">สินค้าหมด</span>
        @endif
    </div>

    @auth
        @if(auth()->user()->is_admin)
            {{-- Admin Buttons --}}
            <div class="mt-6 flex items-center gap-2">
                <a href="{{ route('admin.products.edit', $product) }}" class="px-4 py-2 rounded-lg border hover:bg-gray-50">แก้ไข</a>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('ยืนยันลบ?')">
                    @csrf @method('DELETE')
                    <button class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">ลบ</button>
                </form>
            </div>
        @else
            {{-- Logged-in User "Add to Cart" Form --}}
            @if($product->stock > 0)
                <form action="{{ route('cart.add') }}" method="POST" class="mt-6 flex items-center gap-3">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="number" name="qty" value="1" min="1" max="{{ $product->stock }}" class="w-20 rounded-lg border-gray-300 text-center focus:ring-brand focus:border-brand">
                    <button class="px-5 py-2 rounded-xl bg-brand text-white hover:bg-brand-dark">🛒 ใส่ตะกร้า</button>
                </form>
                {{-- Wishlist Button --}}
                <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="p-3 rounded-xl hover:bg-gray-100 border">
                         @if(in_array($product->id, $wishlistIds ?? []))
                            {{-- Solid Heart (Corrected) --}}
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-brand">
                                <path fill-rule="evenodd" d="M6.32 2.577a4.5 4.5 0 016.364 0l.086.086a4.5 4.5 0 016.364 6.364l-6.5 6.5a.75.75 0 01-1.06 0l-6.5-6.5a4.5 4.5 0 010-6.364l.086-.086z" clip-rule="evenodd" />
                            </svg>
                        @else
                            {{-- Outline Heart --}}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400 hover:text-brand">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>
                        @endif
                    </button>
                </form>
            @else
                <button class="mt-6 w-full px-5 py-2 rounded-xl bg-gray-300 text-gray-500 cursor-not-allowed" disabled>สินค้าหมด</button>
            @endif
        @endif
    @else
        {{-- Guest "Login" Button --}}
        <a href="{{ route('login') }}" class="mt-6 inline-block w-full text-center px-5 py-3 rounded-xl bg-gray-200 text-gray-700 hover:bg-gray-300">
            เข้าสู่ระบบเพื่อสั่งซื้อ
        </a>
    @endauth
  </div>
</div>

{{-- Reviews Section --}}
<div class="mt-12">
    <h2 class="text-2xl font-semibold mb-4">รีวิวสินค้า</h2>

    {{-- Session Messages --}}
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">{{ session('error') }}</div>
    @endif

    {{-- Review Form --}}
    @if ($canReview)
        <div class="bg-white border rounded-2xl p-6 mb-8">
            <h3 class="text-lg font-semibold mb-3">เขียนรีวิวของคุณ</h3>
            <form action="{{ route('reviews.store') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                
                <div class="mb-3">
                    <label for="rating" class="block mb-1 font-medium">คะแนน</label>
                    <select name="rating" id="rating" class="w-full rounded-lg border-gray-300 focus:ring-brand focus:border-brand">
                        <option value="5">5 ดาว - ยอดเยี่ยม</option>
                        <option value="4">4 ดาว - ดี</option>
                        <option value="3">3 ดาว - ปานกลาง</option>
                        <option value="2">2 ดาว - พอใช้</option>
                        <option value="1">1 ดาว - ต้องปรับปรุง</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="comment" class="block mb-1 font-medium">ความคิดเห็น</label>
                    <textarea name="comment" id="comment" rows="4" class="w-full rounded-lg border-gray-300 focus:ring-brand focus:border-brand" required>{{ old('comment') }}</textarea>
                </div>

                <button type="submit" class="px-5 py-2 rounded-xl bg-brand text-white hover:bg-brand-dark">ส่งรีวิว</button>
            </form>
        </div>
    @elseif(Auth::check() && $hasPurchased && $hasReviewed)
         <div class="mb-8 p-4 bg-blue-100 text-blue-700 rounded-lg">คุณได้รีวิวสินค้านี้ไปแล้ว</div>
    @endif


    {{-- Existing Reviews --}}
    <div class="space-y-6">
        @forelse ($product->reviews as $review)
            <div class="bg-white border rounded-2xl p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="font-semibold">{{ $review->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex items-center gap-1 text-yellow-400">
                        @for ($i = 0; $i < $review->rating; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                        @endfor
                    </div>
                </div>
                <p class="mt-3 text-gray-700">{{ $review->comment }}</p>
            </div>
        @empty
            <div class="bg-white border rounded-2xl p-6 text-center text-gray-500">
                ยังไม่มีรีวิวสำหรับสินค้านี้
            </div>
        @endforelse
    </div>
</div>
</div>
@endsection
