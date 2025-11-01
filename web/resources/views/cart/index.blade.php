@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4" style="color: #4B2067;">
        <i class="bi bi-cart3"></i> Giỏ hàng của bạn
    </h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($cartItems->count() > 0)
        <div class="row">
            <!-- Danh sách sản phẩm -->
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Sản phẩm ({{ $cartItems->count() }})</h5>
                            <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i> Xóa tất cả
                                </button>
                            </form>
                        </div>

                        @foreach($cartItems as $item)
                            <div class="row border-bottom py-3 align-items-center">
                                <div class="col-md-2">
                                    @if($item->book->cover_path && file_exists(public_path('storage/' . $item->book->cover_path)))
                                        <img src="{{ asset('storage/' . $item->book->cover_path) }}" class="img-fluid rounded" alt="{{ $item->book->title }}">
                                    @else
                                        <img src="https://via.placeholder.com/100x150?text=No+Image" class="img-fluid rounded" alt="{{ $item->book->title }}">
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <h6 class="fw-bold mb-2">
                                        <a href="{{ route('book.detail', $item->book->id) }}" class="text-decoration-none" style="color: #4B2067;">
                                            {{ $item->book->title }}
                                        </a>
                                    </h6>
                                    <div class="text-muted small">{{ $item->book->author->name ?? 'Không rõ' }}</div>
                                    <div class="text-muted small">{{ $item->book->publisher->name ?? 'Không rõ' }}</div>
                                    @if($item->book->stock < 5)
                                        <div class="badge bg-warning text-dark mt-1">Còn {{ $item->book->stock }} cuốn</div>
                                    @endif
                                </div>
                                <div class="col-md-2 text-center">
                                    <div class="fw-bold" style="color: #F53003;">
                                        {{ number_format($item->price, 0, ',', '.') }}₫
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                        @csrf
                                        <div class="input-group input-group-sm">
                                            <button type="button" class="btn btn-outline-secondary" onclick="decreaseQuantity(this)">-</button>
                                            <input type="number" name="quantity" class="form-control text-center" value="{{ $item->quantity }}" min="1" max="{{ $item->book->stock }}" onchange="this.form.submit()">
                                            <button type="button" class="btn btn-outline-secondary" onclick="increaseQuantity(this, {{ $item->book->stock }})">+</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-2 text-end">
                                    <div class="fw-bold mb-2" style="color: #4B2067;">
                                        {{ number_format($item->subtotal, 0, ',', '.') }}₫
                                    </div>
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa sản phẩm này?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('products.all') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Tiếp tục mua sắm
                </a>
            </div>

            <!-- Tóm tắt đơn hàng -->
            <div class="col-lg-4">
                <div class="card shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">Thông tin đơn hàng</h5>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính:</span>
                            <span class="fw-bold">{{ number_format($cart->total, 0, ',', '.') }}₫</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Giảm giá:</span>
                            <span class="text-danger">-0₫</span>
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

                        @if($cart->total < 150000)
                            <div class="alert alert-info small">
                                Mua thêm <strong>{{ number_format(150000 - $cart->total, 0, ',', '.') }}₫</strong> để được miễn phí vận chuyển!
                            </div>
                        @endif

                        <a href="{{ route('checkout.index') }}" class="btn btn-lg w-100 mb-2" style="background: #4B2067; color: white;">
                            <i class="bi bi-credit-card"></i> Thanh toán
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
    @else
        <div class="text-center py-5">
            <i class="bi bi-cart-x" style="font-size: 5rem; color: #ccc;"></i>
            <h4 class="mt-3 text-muted">Giỏ hàng của bạn đang trống</h4>
            <p class="text-muted">Hãy khám phá và thêm sản phẩm vào giỏ hàng nhé!</p>
            <a href="{{ route('products.all') }}" class="btn btn-lg mt-3" style="background: #4B2067; color: white;">
                Mua sắm ngay
            </a>
        </div>
    @endif
</div>

<script>
    function increaseQuantity(button, maxStock) {
        const input = button.previousElementSibling;
        let value = parseInt(input.value);
        if (value < maxStock) {
            input.value = value + 1;
            input.form.submit();
        }
    }

    function decreaseQuantity(button) {
        const input = button.nextElementSibling;
        let value = parseInt(input.value);
        if (value > 1) {
            input.value = value - 1;
            input.form.submit();
        }
    }
</script>
@endsection
