@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Ảnh bìa sách -->
        <div class="col-md-4">
            <div class="card border-0 shadow-lg">
                @if($book->cover_path && file_exists(public_path('storage/' . $book->cover_path)))
                    <img src="{{ asset('storage/' . $book->cover_path) }}" class="card-img-top" alt="{{ $book->title }}" style="height: 500px; object-fit: cover;">
                @else
                    <img src="https://via.placeholder.com/300x500?text={{ urlencode($book->title) }}" class="card-img-top" alt="{{ $book->title }}" style="height: 500px; object-fit: cover;">
                @endif
            </div>
        </div>

        <!-- Thông tin sách -->
        <div class="col-md-8">
            <div class="mb-2">
                <span class="badge bg-secondary">{{ $book->category->name ?? 'Chưa phân loại' }}</span>
                @if($book->type)
                    <span class="badge bg-info">{{ $book->type }}</span>
                @endif
            </div>
            
            <h1 class="fw-bold mb-3" style="color: #4B2067;">{{ $book->title }}</h1>
            
            <div class="mb-3">
                <div class="d-flex align-items-center mb-2">
                    <span class="text-warning me-2" style="font-size: 1.2em;">★★★★☆</span>
                    <span class="text-muted">(4.5/5 - 128 đánh giá)</span>
                </div>
            </div>

            <table class="table table-borderless mb-4">
                <tbody>
                    <tr>
                        <td class="fw-bold" style="width: 150px;">Tác giả:</td>
                        <td>{{ $book->author->name ?? 'Không rõ' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Nhà xuất bản:</td>
                        <td>{{ $book->publisher->name ?? 'Không rõ' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Danh mục:</td>
                        <td>
                            <a href="{{ route('category.show', $book->category_id) }}" class="text-decoration-none">
                                {{ $book->category->name ?? 'Chưa phân loại' }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Tồn kho:</td>
                        <td>
                            @if($book->stock > 0)
                                <span class="badge bg-success">Còn hàng ({{ $book->stock }} cuốn)</span>
                            @else
                                <span class="badge bg-danger">Hết hàng</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="mb-4">
                <div class="d-flex align-items-center mb-3">
                    <h2 class="fw-bold mb-0" style="color: #F53003; font-size: 2.5em;">
                        {{ number_format($book->price, 0, ',', '.') }}₫
                    </h2>
                    @if($book->price < 200000)
                        <span class="badge bg-danger ms-3" style="font-size: 1em;">-15%</span>
                    @endif
                </div>
                @if($book->price < 200000)
                    <div class="text-muted">
                        <s>{{ number_format($book->price / 0.85, 0, ',', '.') }}₫</s>
                    </div>
                @endif
            </div>

            <div class="d-flex gap-3 mb-4">
                <form action="{{ route('cart.add', $book->id) }}" method="POST" class="d-flex gap-3">
                    @csrf
                    <div class="input-group" style="width: 150px;">
                        <button class="btn btn-outline-secondary" type="button" onclick="decreaseQty()">-</button>
                        <input type="number" id="quantity" name="quantity" class="form-control text-center" value="1" min="1" max="{{ $book->stock }}">
                        <button class="btn btn-outline-secondary" type="button" onclick="increaseQty({{ $book->stock }})">+</button>
                    </div>
                    <button type="submit" class="btn btn-lg px-5" style="background: #4B2067; color: white;" {{ $book->stock == 0 ? 'disabled' : '' }}>
                        <i class="bi bi-cart-plus"></i> {{ $book->stock == 0 ? 'Hết hàng' : 'Thêm vào giỏ hàng' }}
                    </button>
                </form>
                <button class="btn btn-lg btn-outline-danger">
                    <i class="bi bi-heart"></i> Yêu thích
                </button>
            </div>

            <script>
                function increaseQty(max) {
                    const input = document.getElementById('quantity');
                    if (parseInt(input.value) < max) {
                        input.value = parseInt(input.value) + 1;
                    }
                }
                function decreaseQty() {
                    const input = document.getElementById('quantity');
                    if (parseInt(input.value) > 1) {
                        input.value = parseInt(input.value) - 1;
                    }
                }
            </script>

            <div class="alert alert-info">
                <strong>Ưu đãi:</strong> Miễn phí vận chuyển cho đơn hàng trên 150,000₫
            </div>
        </div>
    </div>

    <!-- Mô tả sản phẩm -->
    <div class="row mt-5">
        <div class="col-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button">Mô tả</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button">Đánh giá</button>
                </li>
            </ul>
            <div class="tab-content p-4 border border-top-0" id="myTabContent">
                <div class="tab-pane fade show active" id="description" role="tabpanel">
                    <h5 class="fw-bold mb-3">Mô tả sản phẩm</h5>
                    <p style="white-space: pre-line;">{{ $book->description ?? 'Chưa có mô tả cho sản phẩm này.' }}</p>
                </div>
                <div class="tab-pane fade" id="reviews" role="tabpanel">
                    <h5 class="fw-bold mb-3">Đánh giá từ khách hàng</h5>
                    <p class="text-muted">Chưa có đánh giá nào.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sản phẩm liên quan -->
    @if($relatedBooks->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="fw-bold mb-4" style="color: #4B2067;">Sản phẩm liên quan</h3>
        </div>
        @foreach($relatedBooks as $relatedBook)
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm">
                @if($relatedBook->cover_path && file_exists(public_path('storage/' . $relatedBook->cover_path)))
                    <img src="{{ asset('storage/' . $relatedBook->cover_path) }}" class="card-img-top" alt="{{ $relatedBook->title }}" style="height: 220px; object-fit: cover;">
                @else
                    <img src="https://via.placeholder.com/150x220?text=No+Image" class="card-img-top" alt="{{ $relatedBook->title }}" style="height: 220px; object-fit: cover;">
                @endif
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title fw-bold" style="color: #4B2067; min-height: 40px; font-size: 0.95em;">{{ $relatedBook->title }}</h6>
                    <div class="mb-1 text-muted" style="font-size: 0.85em;">{{ $relatedBook->author->name ?? 'Không rõ' }}</div>
                    <div class="fw-bold mb-2" style="color: #F53003; font-size: 1.05em;">
                        {{ number_format($relatedBook->price, 0, ',', '.') }}₫
                    </div>
                    <div class="mt-auto">
                        <a href="{{ route('book.detail', $relatedBook->id) }}" class="btn btn-outline-dark btn-sm w-100">Xem chi tiết</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
