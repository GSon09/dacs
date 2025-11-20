@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <div class="mb-4">
            <i class="bi bi-check-circle-fill" style="font-size: 5rem; color: #28a745;"></i>
        </div>
        <h2 class="fw-bold mb-3" style="color: #4B2067;">Đặt hàng thành công!</h2>
        <p class="text-muted">Cảm ơn bạn đã đặt hàng. Chúng tôi sẽ liên hệ với bạn sớm nhất.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-4">Thông tin đơn hàng</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Mã đơn hàng:</strong><br>
                            <span class="text-primary">{{ $order->order_number }}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Ngày đặt:</strong><br>
                            {{ $order->order_date->format('d/m/Y H:i') }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Tên khách hàng:</strong><br>
                            {{ $order->customer_name }}
                        </div>
                        <div class="col-md-6">
                            <strong>Số điện thoại:</strong><br>
                            {{ $order->customer_phone }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Email:</strong><br>
                            {{ $order->customer_email }}
                        </div>
                        <div class="col-md-6">
                            <strong>Trạng thái:</strong><br>
                            <span class="badge bg-warning">Chờ xử lý</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <strong>Địa chỉ giao hàng:</strong><br>
                        {{ $order->customer_address }}
                    </div>

                    <hr>

                    <h6 class="fw-bold mb-3">Chi tiết đơn hàng</h6>
                    <div class="table-responsive">
                        <table class="table">
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
                                            <div class="d-flex align-items-center">
                                                @php $cover = $item->book->cover_path ?? null; @endphp
                                                @if($cover && \Illuminate\Support\Facades\Storage::disk('public')->exists($cover))
                                                    <img src="{{ asset('storage/' . $cover) }}" 
                                                         alt="{{ $item->book->title }}" 
                                                         style="width: 50px; height: 70px; object-fit: cover;" 
                                                         class="me-3 rounded">
                                                @else
                                                    <img src="https://via.placeholder.com/50x70?text=No+Image" 
                                                         alt="{{ $item->book->title }}" 
                                                         style="width: 50px; height: 70px; object-fit: cover;" 
                                                         class="me-3 rounded">
                                                @endif
                                                <div>
                                                    <strong>{{ $item->book->title }}</strong><br>
                                                    <small class="text-muted">{{ $item->book->author->name ?? 'Không rõ' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">{{ number_format($item->price, 0, ',', '.') }}₫</td>
                                        <td class="text-end fw-bold">{{ number_format($item->subtotal, 0, ',', '.') }}₫</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Tạm tính:</strong></td>
                                    <td class="text-end">{{ number_format($order->price, 0, ',', '.') }}₫</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Phí vận chuyển:</strong></td>
                                    <td class="text-end">
                                        @php
                                            $shipping = $order->total - $order->price;
                                        @endphp
                                        {{ $shipping > 0 ? number_format($shipping, 0, ',', '.') . '₫' : 'Miễn phí' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><h5 class="fw-bold mb-0">Tổng cộng:</h5></td>
                                    <td class="text-end"><h5 class="fw-bold mb-0" style="color: #F53003;">{{ number_format($order->total, 0, ',', '.') }}₫</h5></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($order->notes)
                        <div class="alert alert-info mt-3">
                            <strong>Ghi chú:</strong> {{ $order->notes }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Reminder to review after delivery -->
            @if($order->status === 'delivered')
                <div class="alert alert-success mt-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-star-fill me-3" style="font-size: 2em;"></i>
                        <div>
                            <h6 class="mb-1">Đơn hàng đã được giao!</h6>
                            <p class="mb-2">Hãy đánh giá sản phẩm để chia sẻ trải nghiệm của bạn</p>
                            <div>
                                @foreach($order->items as $item)
                                    <a href="{{ route('book.detail', $item->book_id) }}#reviews" class="btn btn-sm btn-warning me-2 mb-2">
                                        <i class="bi bi-star"></i> Đánh giá: {{ $item->book->title }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info mt-4">
                    <i class="bi bi-info-circle"></i> 
                    Sau khi nhận hàng, bạn có thể đánh giá sản phẩm để giúp người mua khác!
                </div>
            @endif

            <div class="text-center mt-4">
                <a href="{{ route('home.index') }}" class="btn btn-lg" style="background: #4B2067; color: white;">
                    <i class="bi bi-house"></i> Về trang chủ
                </a>
                <a href="{{ route('products.all') }}" class="btn btn-outline-secondary btn-lg ms-2">
                    <i class="bi bi-cart"></i> Tiếp tục mua sắm
                </a>
                        @if($order->user_id === auth()->id() && !in_array($order->status, ['delivered','canceled']))
                            <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="d-inline ms-2" onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn này?');">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-lg ms-2"> <i class="bi bi-x-circle"></i> Hủy đơn</button>
                            </form>
                        @endif
                        @if($order->user_id === auth()->id())
                            <form action="{{ route('orders.reorder', $order->id) }}" method="POST" class="d-inline ms-2">
                                @csrf
                                <button type="submit" class="btn btn-success btn-lg ms-2"><i class="bi bi-arrow-repeat"></i> Mua Lại</button>
                            </form>
                        @endif
            </div>
        </div>
    </div>
</div>
@endsection
