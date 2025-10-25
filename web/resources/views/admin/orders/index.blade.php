@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Danh sách đơn hàng</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

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
                                        Chờ xử lý
                                        @break
                                    @case('delivered')
                                        Đã giao
                                        @break
                                    @case('canceled')
                                        Đã hủy
                                        @break
                                    @case('waiting_pickup')
                                        Chờ lấy hàng
                                        @break
                                    @default
                                        {{ $order->status }}
                                @endswitch
                            </td>
                            <td>{{ $order->order_date }}</td>
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
