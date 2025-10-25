@extends('layouts.admin')
@php use Carbon\Carbon; @endphp
@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Bảng quản trị</h1>
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title">Tổng đơn hàng</h6>
                    <h3>{{ $totalOrders }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title">Đơn đã giao</h6>
                    <h3>{{ $deliveredOrders }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title">Tổng thu nhập</h6>
                    <h3>{{ number_format($totalIncome, 0, ',', '.') }}₫</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title">Đơn chờ lấy hàng</h6>
                    <h3>{{ $waitingPickupOrders }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title">Đơn hủy</h6>
                    <h3>{{ $canceledOrders }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title">Thu nhập đơn đã giao</h6>
                    <h3>{{ number_format($totalIncome, 0, ',', '.') }}₫</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Doanh thu 7 ngày gần nhất</h6>
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Đơn gần đây</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên</th>
                                    <th>SĐT</th>
                                    <th>Giá</th>
                                    <th>Thuế</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày đặt</th>
                                    <th>Số đặt</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $i => $order)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>{{ $order->customer_phone }}</td>
                                    <td>{{ number_format($order->price, 0, ',', '.') }}₫</td>
                                    <td>{{ number_format($order->tax, 0, ',', '.') }}₫</td>
                                    <td>{{ number_format($order->total, 0, ',', '.') }}₫</td>
                                    <td>{{ ucfirst($order->status) }}</td>
                                    <td>{{ \Illuminate\Support\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}</td>
                                    <td>{{ $order->order_number }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const chartData = {
        labels: {!! json_encode($revenueChart->pluck('date')) !!},
        datasets: [{
            label: 'Doanh thu',
            data: {!! json_encode($revenueChart->pluck('revenue')) !!},
            backgroundColor: 'rgba(75, 32, 103, 0.2)',
            borderColor: '#4B2067',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    };
    new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection