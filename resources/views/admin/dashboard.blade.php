@extends('admin.layout')
@section('title', 'แดชบอร์ด')

@section('admin_content')
    <h1 class="text-2xl font-bold mb-6">แดชบอร์ดและภาพรวม</h1>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white border rounded-lg shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-500">ยอดขายวันนี้</h3>
            <p class="text-3xl font-bold mt-1">฿{{ number_format($salesToday, 2) }}</p>
        </div>
        <div class="bg-white border rounded-lg shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-500">ยอดขายเดือนนี้</h3>
            <p class="text-3xl font-bold mt-1">฿{{ number_format($salesThisMonth, 2) }}</p>
        </div>
        <div class="bg-white border rounded-lg shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-500">ออเดอร์รอตรวจสอบ</h3>
            <p class="text-3xl font-bold mt-1">{{ number_format($pendingOrders) }}</p>
        </div>
        <div class="bg-white border rounded-lg shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-500">ลูกค้าทั้งหมด</h3>
            <p class="text-3xl font-bold mt-1">{{ number_format($totalCustomers) }}</p>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Sales Chart (2/3 width) --}}
        <div class="lg:col-span-2 bg-white border rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4">ยอดขาย 7 วันล่าสุด</h3>
            <canvas id="salesChart"></canvas>
        </div>

        {{-- Right Column --}}
        <div class="space-y-8">
            {{-- Low Stock Products --}}
            <div class="bg-white border rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold mb-4">สินค้าใกล้หมดสต็อก (ต่ำกว่า 10 ชิ้น)</h3>
                @if($lowStockProducts->isEmpty())
                    <p class="text-gray-500 text-center mt-8">ไม่มีสินค้าใกล้หมดสต็อก</p>
                @else
                    <ul class="divide-y">
                        @foreach($lowStockProducts as $product)
                            <li class="py-3 flex justify-between items-center">
                                <div>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="font-medium hover:text-brand">{{ $product->name }}</a>
                                </div>
                                <span class="font-bold text-red-500">{{ $product->stock }} ชิ้น</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Best Selling Products --}}
            <div class="bg-white border rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold mb-4">5 อันดับสินค้าขายดีประจำเดือนนี้</h3>
                @if($bestSellingProducts->isEmpty())
                    <p class="text-gray-500 text-center mt-8">ยังไม่มีข้อมูลสินค้าขายดีในเดือนนี้</p>
                @else
                    <ul class="divide-y">
                        @foreach($bestSellingProducts as $product)
                            <li class="py-3 flex justify-between items-center">
                                <div>
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="font-medium hover:text-brand">{{ $product->name }}</a>
                                </div>
                                <span class="font-bold text-gray-700">{{ $product->total_sold }} ชิ้น</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'ยอดขาย',
                data: @json($chartData),
                fill: true,
                borderColor: '#ec4899', // brand color
                backgroundColor: '#ec489920', // brand color with transparency
                tension: 0.2
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '฿' + new Intl.NumberFormat().format(value);
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += '฿' + new Intl.NumberFormat().format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
