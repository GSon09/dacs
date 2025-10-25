@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Sửa sách</h1>
        <a href="{{ route('books.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
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
            <form action="{{ route('books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label">Tên sách</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $book->title) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tác giả</label>
                    <select name="author_id" class="form-control">
                        <option value="">-- Chọn tác giả --</option>
                        @foreach($authors as $author)
                            <option value="{{ $author->id }}" {{ old('author_id', $book->author_id) == $author->id ? 'selected' : '' }}>
                                {{ $author->name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Hoặc thêm tác giả mới bên dưới</small>
                    <input type="text" name="author_new" class="form-control mt-2" placeholder="Tên tác giả mới" value="{{ old('author_new') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Danh mục</label>
                    @php
                        // Only include these categories in the specified order
                        $allowed = [
                            'Văn học','Kinh tế','Tâm lý - kĩ năng sống','Nuôi dạy con','Sách thiếu nhi','Tiểu sử - Hồi ký','Sách mới',
                            'Lớp 1','Lớp 2','Lớp 3','Lớp 4','Lớp 5','Lớp 6','Lớp 7','Lớp 8','Lớp 9','Lớp 10','Lớp 11','Lớp 12',
                            'Tiếng Anh','Nhật','Hoa','Hàn'
                        ];
                        $byName = $categories->keyBy('name');
                    @endphp
                    <select name="category_id" class="form-control" required>
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($allowed as $name)
                            @if(isset($byName[$name]))
                                @php $cat = $byName[$name]; @endphp
                                <option value="{{ $cat->id }}" {{ old('category_id', $book->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nhà xuất bản</label>
                    <select name="publisher_id" class="form-control">
                        <option value="">-- Chọn nhà xuất bản --</option>
                        @foreach($publishers as $publisher)
                            <option value="{{ $publisher->id }}" {{ old('publisher_id', $book->publisher_id) == $publisher->id ? 'selected' : '' }}>
                                {{ $publisher->name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Hoặc thêm nhà xuất bản mới bên dưới</small>
                    <input type="text" name="publisher_new" class="form-control mt-2" placeholder="Tên nhà xuất bản mới" value="{{ old('publisher_new') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Giá tiền</label>
                    <input type="number" step="1" name="price" class="form-control" value="{{ old('price', $book->price) }}" required>
                    <small class="text-muted">Đơn vị: VND</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tồn kho</label>
                    <input type="number" name="stock" class="form-control" value="{{ old('stock', $book->stock) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description', $book->description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Ảnh bìa hiện tại</label>
                    @if($book->cover_path && file_exists(public_path('storage/' . $book->cover_path)))
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $book->cover_path) }}" alt="{{ $book->title }}" style="max-width: 200px; height: auto;" class="img-thumbnail">
                        </div>
                    @else
                        <p class="text-muted">Chưa có ảnh bìa</p>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Thay đổi ảnh bìa</label>
                    <input type="file" name="cover" class="form-control" accept="image/*">
                    <small class="text-muted">Để trống nếu không muốn thay đổi ảnh bìa</small>
                </div>

                <button class="btn btn-primary">Cập nhật</button>
                <a href="{{ route('books.index') }}" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
</div>
@endsection
