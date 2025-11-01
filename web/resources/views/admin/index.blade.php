@extends('layouts.admin')
@php use Carbon\Carbon; @endphp
@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Bảng quản trị</h1>
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary text-center">
                <div class="card-body">
                    <h6 class="card-title">Tổng đơn hàng</h6>
                    <h2 class="mb-0">{{ $totalOrders }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success text-center">
                <div class="card-body">
                    <h6 class="card-title">Đơn đã giao</h6>
                    <h2 class="mb-0">{{ $deliveredOrders }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning text-center">
                <div class="card-body">
                    <h6 class="card-title">Đơn chờ xử lý</h6>
                    <h2 class="mb-0">{{ $pendingOrders }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger text-center">
                <div class="card-body">
                    <h6 class="card-title">Đơn đã hủy</h6>
                    <h2 class="mb-0">{{ $canceledOrders }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-info text-center">
                <div class="card-body">
                    <h6 class="card-title">Tổng doanh thu</h6>
                    <h2 class="mb-0">{{ number_format($totalIncome, 0, ',', '.') }}₫</h2>
                    <small>Từ đơn đã giao</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-warning text-center">
                <div class="card-body">
                    <h6 class="card-title">Đơn chờ lấy hàng</h6>
                    <h2 class="mb-0 text-warning">{{ $waitingPickupOrders }}</h2>
                    <small class="text-muted">{{ number_format($pendingOrderAmount, 0, ',', '.') }}₫</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-danger text-center">
                <div class="card-body">
                    <h6 class="card-title">Đơn đã hủy</h6>
                    <h2 class="mb-0 text-danger">{{ $canceledOrders }}</h2>
                    <small class="text-muted">{{ number_format($canceledOrderAmount, 0, ',', '.') }}₫</small>
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
                                    <th>Mã đơn</th>
                                    <th>Tên khách hàng</th>
                                    <th>SĐT</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày đặt</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $i => $order)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td><strong>{{ $order->order_number }}</strong></td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>{{ $order->customer_phone }}</td>
                                    <td>{{ number_format($order->total, 0, ',', '.') }}₫</td>
                                    <td>
                                        @switch($order->status)
                                            @case('pending')
                                                <span class="badge bg-warning">Chờ xử lý</span>
                                                @break
                                            @case('delivered')
                                                <span class="badge bg-success">Đã giao</span>
                                                @break
                                            @case('canceled')
                                                <span class="badge bg-danger">Đã hủy</span>
                                                @break
                                            @case('waiting_pickup')
                                                <span class="badge bg-info">Chờ lấy hàng</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>{{ \Illuminate\Support\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-info">Xem</a>
                                    </td>
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