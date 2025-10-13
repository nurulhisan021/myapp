@extends('admin.layout')
@section('title', 'ตั้งค่าบัญชีธนาคาร')

@section('admin_content')
<h1 class="text-2xl font-bold mb-6">ตั้งค่าบัญชีธนาคาร</h1>

@if($errors->any())
    <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 p-4 text-sm">
      <strong class="font-bold">เกิดข้อผิดพลาด!</strong>
      <ul class="list-disc list-inside mt-1">
        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
@endif

@if (session('success'))
    <div class="mb-4 p-4 rounded-md bg-green-100 text-green-800">
        {{ session('success') }}
    </div>
@endif

<div class="max-w-lg bg-white border rounded-lg shadow-sm p-6">
    <form action="{{ route('admin.bank-account.update') }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-1">ชื่อธนาคาร</label>
            <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name', $bankAccount->bank_name) }}" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand focus:border-brand">
        </div>
        <div>
            <label for="account_name" class="block text-sm font-medium text-gray-700 mb-1">ชื่อบัญชี</label>
            <input type="text" name="account_name" id="account_name" value="{{ old('account_name', $bankAccount->account_name) }}" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand focus:border-brand">
        </div>
        <div>
            <label for="account_number" class="block text-sm font-medium text-gray-700 mb-1">เลขที่บัญชี</label>
            <input type="text" name="account_number" id="account_number" value="{{ old('account_number', $bankAccount->account_number) }}" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand focus:border-brand">
        </div>
        <div class="flex items-start">
            <div class="flex items-center h-5">
                <input id="is_active" name="is_active" type="checkbox" value="1" {{ $bankAccount->is_active ? 'checked' : '' }} class="focus:ring-brand h-4 w-4 text-brand border-gray-300 rounded">
            </div>
            <div class="ml-3 text-sm">
                <label for="is_active" class="font-medium text-gray-700">เปิดใช้งาน</label>
                <p class="text-gray-500">ให้ลูกค้ามองเห็นบัญชีนี้ในหน้าชำระเงิน</p>
            </div>
        </div>

        <div class="pt-4 space-y-2">
            <label class="block text-sm font-medium text-gray-700">QR Code (PromptPay)</label>
            <div class="flex items-center gap-4">
                <img id="qr-preview" src="{{ $bankAccount->qr_code_url ?? asset('images/product-placeholder.png') }}" class="w-24 h-24 rounded-lg object-cover border p-1">
                <input type="file" name="qr_code" id="qr_code" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-brand hover:file:bg-pink-100">
            </div>
            @if($bankAccount->qr_code_path)
            <label class="flex items-center gap-2 text-xs text-gray-600">
                <input type="checkbox" name="remove_qr_code" value="1" class="rounded border-gray-300 text-brand shadow-sm focus:ring-brand">
                <span>ลบ QR Code ปัจจุบัน</span>
            </label>
            @endif
        </div>

        <div class="pt-4 border-t">
            <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                บันทึกการตั้งค่า
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
  const input = document.getElementById('qr_code');
  const img = document.getElementById('qr-preview');
  input?.addEventListener('change', () => {
    const [file] = input.files;
    if (file) {
      img.src = URL.createObjectURL(file);
    }
  });
</script>
@endpush
@endsection
