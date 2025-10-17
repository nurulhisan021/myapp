@extends('layouts.app')
@section('title', 'จัดการที่อยู่')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">จัดการที่อยู่</h1>
        @if($canAddAddress)
            <a href="{{ route('account.addresses.create') }}" class="px-4 py-2 rounded-lg bg-brand text-white hover:bg-brand-dark">
                + เพิ่มที่อยู่ใหม่
            </a>
        @endif
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 rounded-md bg-green-100 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-4">
        @forelse($addresses as $address)
            <div class="bg-white border rounded-lg shadow-sm p-4 flex justify-between items-start">
                <div>
                    <p class="font-semibold">{{ $address->name }}</p>
                    <p class="text-gray-600 text-sm whitespace-pre-line">{{ $address->address }}</p>
                    <p class="text-gray-600 text-sm mt-1">เบอร์โทร: {{ $address->phone }}</p>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0 ml-4">
                    <a href="{{ route('account.addresses.edit', $address) }}" class="px-3 py-1 rounded-md border text-xs font-medium hover:bg-gray-50">แก้ไข</a>
                    <form action="{{ route('account.addresses.destroy', $address) }}" method="POST" onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบที่อยู่นี้?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1 rounded-md bg-red-100 text-red-700 text-xs font-medium hover:bg-red-200">ลบ</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white border rounded-lg shadow-sm p-8 text-center text-gray-500">
                <p>คุณยังไม่มีที่อยู่ที่บันทึกไว้</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
