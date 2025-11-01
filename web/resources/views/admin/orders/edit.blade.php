@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Chỉnh sửa đơn hàng #{{ $order->order_number }}</h1>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><strong>Mã đơn hàng:</strong></label>
                                    <input type="text" class="form-control" value="{{ $order->order_number }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><strong>Ngày đặt:</strong></label>
                                    <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><strong>Tên khách hàng:</strong></label>
                                    <input type="text" class="form-control" value="{{ $order->customer_name }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><strong>Số điện thoại:</strong></label>
                                    <input type="text" class="form-control" value="{{ $order->customer_phone }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><strong>Trạng thái đơn hàng:</strong> <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="waiting_pickup" {{ $order->status == 'waiting_pickup' ? 'selected' : '' }}>Chờ lấy hàng</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Đã giao</option>
                                <option value="canceled" {{ $order->status == 'canceled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Lưu ý: Khi đổi trạng thái sang "Đã hủy", số lượng sản phẩm sẽ được hoàn trả về kho khi xóa đơn hàng.
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><strong>Tổng tiền:</strong></label>
                            <input type="text" class="form-control" value="{{ number_format($order->total, 0, ',', '.') }}₫" readonly>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-secondary">Hủy</a>
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Chi tiết khách hàng</h5>
                </div>
                <div class="card-body">
                    <p><strong>Tên:</strong> {{ $order->customer_name }}</p>
                    <p><strong>Email:</strong> {{ $order->customer_email ?? 'N/A' }}</p>
                    <p><strong>Số điện thoại:</strong> {{ $order->customer_phone }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $order->customer_address ?? 'N/A' }}</p>
                    @if($order->notes)
                        <p><strong>Ghi chú:</strong> {{ $order->notes }}</p>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Sản phẩm ({{ $order->items->count() }})</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        @foreach($order->items as $item)
                            <li class="mb-2">
                                <strong>{{ $item->book->title }}</strong><br>
                                <small>SL: {{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}₫</small>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
