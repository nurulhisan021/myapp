@extends('admin.layout')
@section('title', 'จัดการแอดมิน')

@section('admin_content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">จัดการแอดมิน</h1>
    @can('create', App\Models\User::class)
    <a href="{{ route('admin.admins.create') }}" class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">
        + เพิ่มแอดมินใหม่
    </a>
    @endcan
</div>

{{-- Display success/error messages --}}
@if (session('success'))
    <div class="mb-4 p-4 rounded-md bg-green-100 text-green-800">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white border rounded-lg shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left min-w-[600px]">
        <thead class="border-b">
            <tr>
                <th class="p-4">#</th>
                <th class="p-4">ชื่อ</th>
                <th class="p-4">อีเมล</th>
                <th class="p-4">วันที่สร้าง</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($admins as $admin)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4">{{ $admins->firstItem() + $loop->index }}</td>
                    <td class="p-4 font-semibold">{{ $admin->name }}</td>
                    <td class="p-4">{{ $admin->email }}</td>
                    <td class="p-4">{{ $admin->created_at->format('d/m/Y') }}</td>
<td class="p-4 flex gap-2">
                        @can('update', $admin)
                        <a href="{{ route('admin.admins.edit', $admin) }}" class="text-blue-600 hover:underline">แก้ไข</a>
                        @endcan
                        @can('delete', $admin)
                        <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบแอดมินคนนี้?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">ลบ</button>
                        </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-4 text-center text-gray-500">
                        ยังไม่มีแอดมิน
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($admins->hasPages())
        <div class="p-4 border-t">
            {{ $admins->links() }}
        </div>
    @endif
</div>
@endsection
