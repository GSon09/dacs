@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Thêm người dùng</h1>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
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
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Tên người dùng</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Quyền</label>
                    <select name="role" class="form-control" required>
                        <option value="">-- Chọn quyền --</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                        <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Nhân viên</option>
                        <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Khách hàng</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button class="btn btn-success">Lưu</button>
            </form>
        </div>
    </div>
</div>
@endsection
