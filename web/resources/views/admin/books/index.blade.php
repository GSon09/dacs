@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Danh sách</h1>
        <a href="{{ route('books.create') }}" class="btn btn-primary">Thêm sách mới</a>
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
                            <th>Ảnh bìa</th>
                            <th>Tên sách</th>
                            <th>Tác giả</th>
                            <th>Danh mục</th>
                            <th>Nhà xuất bản</th>
                            <th>Giá tiền</th>
                            <th>Tồn kho</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($books as $book)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($book->cover_path && file_exists(public_path('storage/' . $book->cover_path)))
                                    <img src="{{ asset('storage/' . $book->cover_path) }}" alt="{{ $book->title }}" style="width: 50px; height: auto;">
                                @else
                                    <span class="text-muted">Không có</span>
                                @endif
                            </td>
                            <td>{{ $book->title }}</td>
                            <td>{{ $book->author?->name }}</td>
                            <td>{{ $book->category?->name }}</td>
                            <td>{{ $book->publisher?->name }}</td>
                            <td>{{ number_format($book->price, 0, ',', '.') }}₫</td>
                            <td>{{ $book->stock }}</td>
                            <td>
                                <a href="{{ route('books.edit', $book->id) }}" class="btn btn-sm btn-secondary">Sửa</a>
                                <form action="{{ route('books.destroy', $book) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa sách này?')">Xóa</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $books->links() }}</div>
        </div>
    </div>
</div>
@endsection
