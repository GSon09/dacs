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
                    @php
                        $avgRating = $book->averageRating();
                        $totalReviews = $book->totalReviews();
                        $fullStars = floor($avgRating);
                        $halfStar = ($avgRating - $fullStars) >= 0.5;
                        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                    @endphp
                    <span class="text-warning me-2" style="font-size: 1.2em;">
                        @for($i = 0; $i < $fullStars; $i++)★@endfor
                        @if($halfStar)★@endif
                        @for($i = 0; $i < $emptyStars; $i++)☆@endfor
                    </span>
                    <span class="text-muted">
                        @if($totalReviews > 0)
                            ({{ number_format($avgRating, 1) }}/5 - {{ $totalReviews }} đánh giá)
                        @else
                            (Chưa có đánh giá)
                        @endif
                    </span>
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
                    <h5 class="fw-bold mb-4">Đánh giá từ khách hàng</h5>
                    
                    <!-- Tổng quan đánh giá -->
                    <div class="row mb-4">
                        <div class="col-md-4 text-center border-end">
                            <div class="mb-2">
                                <h1 class="display-3 fw-bold text-warning mb-0">{{ number_format($avgRating, 1) }}</h1>
                                <div class="text-warning fs-4">
                                    @for($i = 0; $i < $fullStars; $i++)★@endfor
                                    @if($halfStar)★@endif
                                    @for($i = 0; $i < $emptyStars; $i++)☆@endfor
                                </div>
                                <p class="text-muted">{{ $totalReviews }} đánh giá</p>
                            </div>
                        </div>
                        <div class="col-md-8">
                            @php
                                $ratingCounts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
                                foreach($book->reviews as $review) {
                                    $ratingCounts[$review->rating]++;
                                }
                            @endphp
                            @foreach([5,4,3,2,1] as $star)
                                <div class="d-flex align-items-center mb-2">
                                    <span class="me-2" style="width: 80px;">{{ $star }} sao</span>
                                    <div class="progress flex-grow-1 me-2" style="height: 10px;">
                                        @php
                                            $percentage = $totalReviews > 0 ? ($ratingCounts[$star] / $totalReviews) * 100 : 0;
                                        @endphp
                                        <div class="progress-bar bg-warning" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="text-muted" style="width: 50px;">{{ $ratingCounts[$star] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Form đánh giá (chỉ hiện nếu đã mua) -->
                    <div id="review-form-container" class="mb-4">
                        @auth
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3">Viết đánh giá của bạn</h6>
                                    <div id="review-eligibility-check">
                                        <div class="text-center py-3">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> 
                                <a href="{{ route('login') }}">Đăng nhập</a> để viết đánh giá
                            </div>
                        @endauth
                    </div>

                    <!-- Danh sách đánh giá -->
                    <div id="reviews-list">
                        @forelse($book->reviews()->orderBy('is_verified_purchase', 'desc')->orderBy('created_at', 'desc')->get() as $review)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <div class="d-flex align-items-center mb-1">
                                                <strong class="me-2">{{ $review->getReviewerDisplayName() }}</strong>
                                                @if($review->is_verified_purchase)
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle"></i> Đã mua hàng
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-warning mb-1">
                                                @for($i = 0; $i < $review->rating; $i++)★@endfor
                                                @for($i = $review->rating; $i < 5; $i++)☆@endfor
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                    </div>
                                    @if($review->comment)
                                        <p class="mb-0">{{ $review->comment }}</p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-chat-square-text" style="font-size: 3em;"></i>
                                <p class="mt-2">Chưa có đánh giá nào. Hãy là người đầu tiên đánh giá sản phẩm này!</p>
                            </div>
                        @endforelse
                    </div>
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

@auth
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if user can review
    fetch('{{ route("review.canReview", $book->id) }}')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('review-eligibility-check');
            
            if (data.has_reviewed) {
                container.innerHTML = `
                    <div class="alert alert-success mb-0">
                        <i class="bi bi-check-circle"></i> Bạn đã đánh giá sản phẩm này rồi!
                    </div>
                `;
            } else if (data.can_review) {
                // Show review form
                container.innerHTML = `
                    <form action="{{ route('review.store', $book->id) }}" method="POST" id="review-form">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Đánh giá của bạn <span class="text-danger">*</span></label>
                            <div class="star-rating mb-2">
                                ${[5,4,3,2,1].map(star => `
                                    <input type="radio" id="star${star}" name="rating" value="${star}" required>
                                    <label for="star${star}" title="${star} sao">★</label>
                                `).join('')}
                            </div>
                            <div id="rating-error" class="text-danger small"></div>
                        </div>
                        ${data.eligible_orders && data.eligible_orders.length > 0 ? `
                            <input type="hidden" name="order_id" value="${data.eligible_orders[0].id}">
                        ` : ''}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nhận xét</label>
                            <textarea name="comment" class="form-control" rows="4" placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Gửi đánh giá
                        </button>
                    </form>
                `;
            } else if (data.has_purchased) {
                container.innerHTML = `
                    <div class="alert alert-warning mb-0">
                        <i class="bi bi-clock"></i> Chỉ có thể đánh giá sau khi đơn hàng được giao
                    </div>
                `;
            } else {
                container.innerHTML = `
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle"></i> Bạn cần mua sản phẩm này để có thể đánh giá
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('review-eligibility-check').innerHTML = `
                <div class="alert alert-danger mb-0">Không thể tải form đánh giá</div>
            `;
        });
});
</script>

<style>
/* Star Rating CSS */
.star-rating {
    direction: rtl;
    display: inline-flex;
    font-size: 2em;
}

.star-rating input[type="radio"] {
    display: none;
}

.star-rating label {
    color: #ddd;
    cursor: pointer;
    padding: 0 5px;
}

.star-rating input[type="radio"]:checked ~ label,
.star-rating label:hover,
.star-rating label:hover ~ label {
    color: #ffc107;
}
</style>
@endauth
@endsection
