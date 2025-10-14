@extends('layouts.app')
@section('title', 'แก้ไขที่อยู่')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">แก้ไขที่อยู่</h1>
        <a href="{{ route('account.addresses.index') }}" class="text-sm text-gray-600 hover:underline">
            &larr; กลับไปที่รายการที่อยู่
        </a>
    </div>

    <div class="bg-white border rounded-lg shadow-sm p-6">
        <form action="{{ route('account.addresses.update', $address) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">ชื่อผู้รับ</label>
                <input type="text" name="name" id="name" value="{{ old('name', $address->name) }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand focus:border-brand">
            </div>
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">ที่อยู่ (บ้านเลขที่, ถนน, ตำบล, อำเภอ, จังหวัด, รหัสไปรษณีย์)</label>
                <textarea name="address" id="address" rows="4" required
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand focus:border-brand">{{ old('address', $address->address) }}</textarea>
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">เบอร์โทรศัพท์</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $address->phone) }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand focus:border-brand">
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full px-6 py-3 rounded-lg bg-brand text-white font-bold hover:bg-brand-dark">
                    บันทึกการเปลี่ยนแปลง
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
