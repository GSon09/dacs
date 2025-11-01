@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Danh sách đơn hàng</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Bộ lọc -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('orders.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tìm kiếm</label>
                    <input type="text" name="search" class="form-control" placeholder="Mã đơn, tên KH, SĐT..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                        <option value="waiting_pickup" {{ request('status') == 'waiting_pickup' ? 'selected' : '' }}>Chờ lấy hàng</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Đã giao</option>
                        <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Lọc</button>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <a href="{{ route('orders.index') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Mã đơn hàng</th>
                            <th>Tên khách hàng</th>
                            <th>Số điện thoại</th>
                            <th>Giá trị đơn hàng</th>
                            <th>Thuế</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt hàng</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->customer_phone }}</td>
                            <td>{{ number_format($order->price, 0, ',', '.') }}₫</td>
                            <td>{{ number_format($order->tax, 0, ',', '.') }}₫</td>
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
                                    @default
                                        {{ $order->status }}
                                @endswitch
                            </td>
                            <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-info">Xem</a>
                                <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-secondary">Sửa</a>
                                <form action="{{ route('orders.destroy', $order) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa đơn hàng này?')">Xóa</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $orders->links() }}</div>
        </div>
    </div>
</div>
@endsection
