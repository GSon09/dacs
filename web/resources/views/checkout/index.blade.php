@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4" style="color: #4B2067;">
        <i class="bi bi-credit-card"></i> Thanh toán
    </h2>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <div class="row">
            <!-- Thông tin giao hàng -->
            <div class="col-lg-7">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4" style="color: #4B2067;">Thông tin giao hàng</h5>
                        
                        <div class="mb-3">
                            <label for="customer_name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                                   id="customer_name" name="customer_name" value="{{ old('customer_name', Auth::user()->name ?? '') }}" required>
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="customer_email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('customer_email') is-invalid @enderror" 
                                   id="customer_email" name="customer_email" value="{{ old('customer_email', Auth::user()->email ?? '') }}" required>
                            @error('customer_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="customer_phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control @error('customer_phone') is-invalid @enderror" 
                                   id="customer_phone" name="customer_phone" value="{{ old('customer_phone', Auth::user()->phone ?? '') }}" required>
                            @error('customer_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="customer_address" class="form-label">Địa chỉ giao hàng <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('customer_address') is-invalid @enderror" 
                                      id="customer_address" name="customer_address" rows="3" required>{{ old('customer_address') }}</textarea>
                            @error('customer_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Ghi chú đơn hàng (Tùy chọn)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2" 
                                      placeholder="Ví dụ: Giao hàng giờ hành chính">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tóm tắt đơn hàng -->
            <div class="col-lg-5">
                <div class="card shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">Đơn hàng của bạn</h5>
                        
                        <!-- Danh sách sản phẩm -->
                        <div class="mb-3" style="max-height: 300px; overflow-y: auto;">
                            @foreach($cartItems as $item)
                                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                    <div class="d-flex align-items-start flex-grow-1">
                                        @if($item->book->cover_path && file_exists(public_path('storage/' . $item->book->cover_path)))
                                            <img src="{{ asset('storage/' . $item->book->cover_path) }}" 
                                                 alt="{{ $item->book->title }}" 
                                                 style="width: 50px; height: 70px; object-fit: cover;" 
                                                 class="me-3 rounded">
                                        @else
                                            <img src="https://via.placeholder.com/50x70?text=No+Image" 
                                                 alt="{{ $item->book->title }}" 
                                                 style="width: 50px; height: 70px; object-fit: cover;" 
                                                 class="me-3 rounded">
                                        @endif
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1" style="font-size: 0.9em;">{{ $item->book->title }}</h6>
                                            <small class="text-muted">Số lượng: {{ $item->quantity }}</small>
                                        </div>
                                    </div>
                                    <div class="fw-bold ms-2" style="color: #4B2067; white-space: nowrap;">
                                        {{ number_format($item->subtotal, 0, ',', '.') }}₫
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <hr>

                        <!-- Tổng tiền -->
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính:</span>
                            <span class="fw-bold">{{ number_format($cart->total, 0, ',', '.') }}₫</span>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span>Phí vận chuyển:</span>
                            <span class="text-muted">
                                @if($cart->total >= 150000)
                                    <s>30,000₫</s> <span class="text-success">Miễn phí</span>
                                @else
                                    30,000₫
                                @endif
                            </span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-4">
                            <h5 class="fw-bold">Tổng cộng:</h5>
                            <h5 class="fw-bold" style="color: #F53003;">
                                {{ number_format($cart->total >= 150000 ? $cart->total : $cart->total + 30000, 0, ',', '.') }}₫
                            </h5>
                        </div>

                        <button type="submit" class="btn btn-lg w-100 mb-2" style="background: #4B2067; color: white;">
                            <i class="bi bi-check-circle"></i> Đặt hàng
                        </button>

                        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary btn-lg w-100">
                            <i class="bi bi-arrow-left"></i> Quay lại giỏ hàng
                        </a>

                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="bi bi-shield-check"></i> Thanh toán an toàn và bảo mật
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
