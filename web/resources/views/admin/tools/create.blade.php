@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Thêm dụng cụ</h1>
        <a href="{{ route('books.index') }}" class="btn btn-secondary">Quay lại danh sách sách</a>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('tools.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Tên dụng cụ</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Giá tiền</label>
                    <input type="number" step="1" name="price" class="form-control" value="{{ old('price') }}" required>
                    <small class="text-muted">Đơn vị: VND</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ảnh bìa</label>
                    <input type="file" name="cover" class="form-control" accept="image/*">
                    <small class="text-muted">Tùy chọn. Ảnh này sẽ dùng làm bìa dụng cụ.</small>
                </div>
                <button class="btn btn-success">Lưu</button>
            </form>
        </div>
    </div>
</div>
@endsection
