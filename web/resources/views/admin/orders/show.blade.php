@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Chi tiết đơn hàng #{{ $order->order_number }}</h1>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>

    <div class="row">
        <!-- Thông tin đơn hàng -->
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Mã đơn hàng:</strong> {{ $order->order_number }}
                        </div>
                        <div class="col-md-6">
                            <strong>Ngày đặt:</strong> {{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Trạng thái:</strong>
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
                        </div>
                        <div class="col-md-6">
                            <strong>Người dùng:</strong> {{ $order->user ? $order->user->name : 'Khách' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Thông tin khách hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <strong>Tên:</strong> {{ $order->customer_name }}
                        </div>
                        <div class="col-md-6">
                            <strong>Email:</strong> {{ $order->customer_email ?? 'N/A' }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <strong>Số điện thoại:</strong> {{ $order->customer_phone }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <strong>Địa chỉ:</strong> {{ $order->customer_address ?? 'N/A' }}
                        </div>
                    </div>
                    @if($order->notes)
                        <div class="row mt-2">
                            <div class="col-12">
                                <strong>Ghi chú:</strong> {{ $order->notes }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Sản phẩm đã đặt</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-end">Đơn giá</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->book->title }}</strong><br>
                                        <small class="text-muted">{{ $item->book->author->name ?? 'Không rõ' }}</small>
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">{{ number_format($item->price, 0, ',', '.') }}₫</td>
                                    <td class="text-end">{{ number_format($item->subtotal, 0, ',', '.') }}₫</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Tạm tính:</strong></td>
                                <td class="text-end">{{ number_format($order->price, 0, ',', '.') }}₫</td>
                            </tr>
                            @if($order->tax > 0)
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Thuế:</strong></td>
                                    <td class="text-end">{{ number_format($order->tax, 0, ',', '.') }}₫</td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="3" class="text-end"><strong>Phí vận chuyển:</strong></td>
                                <td class="text-end">
                                    @php
                                        $shipping = $order->total - $order->price - $order->tax;
                                    @endphp
                                    {{ $shipping > 0 ? number_format($shipping, 0, ',', '.') . '₫' : 'Miễn phí' }}
                                </td>
                            </tr>
                            <tr class="table-active">
                                <td colspan="3" class="text-end"><strong>TỔNG CỘNG:</strong></td>
                                <td class="text-end"><strong>{{ number_format($order->total, 0, ',', '.') }}₫</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Thao tác -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thao tác</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Cập nhật trạng thái:</label>
                            <select name="status" class="form-select">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="waiting_pickup" {{ $order->status == 'waiting_pickup' ? 'selected' : '' }}>Chờ lấy hàng</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Đã giao</option>
                                <option value="canceled" {{ $order->status == 'canceled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mb-2">Cập nhật trạng thái</button>
                    </form>
                    
                    <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-secondary w-100 mb-2">Sửa đơn hàng</a>
                    
                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Bạn có chắc muốn xóa đơn hàng này? Số lượng sản phẩm sẽ được hoàn trả về kho.')">Xóa đơn hàng</button>
                    </form>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin thêm</h5>
                </div>
                <div class="card-body">
                    <p><strong>Tạo lúc:</strong><br>{{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                    <p><strong>Cập nhật:</strong><br>{{ $order->updated_at->format('d/m/Y H:i:s') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
