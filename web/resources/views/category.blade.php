@extends('layouts.app')
@section('content')
<div class="container-fluid py-4" style="background: #f8f9fa; min-height: 100vh;">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-uppercase" style="color: #2d3a4b; letter-spacing: 2px;">
                📚 Sản phẩm thuộc danh mục: <span style="color: #F53003;">{{ $category->name }}</span>
            </h2>
            <p class="text-muted">Tìm thấy {{ $books->total() }} cuốn sách</p>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="row">
                @forelse ($books as $book)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card h-100 border-0 shadow rounded-4">
                            @if($book->cover_path && file_exists(public_path('storage/' . $book->cover_path)))
                                <img src="{{ asset('storage/' . $book->cover_path) }}" class="card-img-top rounded-top-4" alt="{{ $book->title }}" style="height: 220px; object-fit: cover;">
                            @else
                                <img src="https://via.placeholder.com/150x220?text={{ urlencode($book->title) }}" class="card-img-top rounded-top-4" alt="{{ $book->title }}" style="height: 220px; object-fit: cover;">
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold" style="color: #2d3a4b; min-height: 48px;">{{ $book->title }}</h5>
                                <div class="mb-2 text-muted" style="font-size: 0.95em;">Tác giả: {{ $book->author->name ?? 'Không rõ' }}</div>
                                <div class="mb-2 text-muted" style="font-size: 0.95em;">Nhà XB: {{ $book->publisher->name ?? 'Không rõ' }}</div>
                                <div class="fw-bold mb-2" style="color: #F53003; font-size: 1.1em;">
                                    {{ number_format($book->price, 0, ',', '.') }}₫
                                </div>
                                <div class="mt-auto">
                                    <a href="{{ route('book.detail', $book->id) }}" class="btn btn-outline-primary btn-sm w-100">Xem chi tiết</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">Chưa có sách nào trong danh mục này.</div>
                    </div>
                @endforelse
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $books->links() }}
            </div>
        </div>
    </div>
</div>
@endsection