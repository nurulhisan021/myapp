@extends('layouts.app')
@section('title',$product->name)

@section('content')
<div class="max-w-3xl mx-auto bg-white border rounded-2xl overflow-hidden">
  <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-80 object-cover">
  <div class="p-6">
    <h1 class="text-2xl font-semibold">{{ $product->name }}</h1>
    <p class="text-pink-600 font-semibold mt-2 text-lg">{{ number_format($product->price,2) }} บาท</p>
    <p class="mt-4 text-gray-700 whitespace-pre-line">{{ $product->description }}</p>
    <div class="mt-6 flex gap-2">
      <a href="{{ route('products.edit',$product) }}" class="px-4 py-2 rounded-xl border hover:bg-gray-50">แก้ไข</a>
      <a href="{{ route('products.index') }}" class="px-4 py-2 rounded-xl border hover:bg-gray-50">กลับ</a>
    </div>
  </div>
</div>
@endsection
