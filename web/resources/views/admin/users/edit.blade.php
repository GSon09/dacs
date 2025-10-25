@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Sửa thông tin người dùng</h1>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
    </div>
    @if(session('status'))
        <div class="alert alert-warning">{{ session('status') }}</div>
    @endif
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
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Tên người dùng</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Quyền</label>
                    <select name="utype" class="form-control" required>
                        <option value="ADM" {{ old('utype', $user->utype) == 'ADM' ? 'selected' : '' }}>Quản trị viên</option>
                        <option value="USR" {{ old('utype', $user->utype) == 'USR' ? 'selected' : '' }}>Người dùng</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mật khẩu mới (nếu muốn đổi)</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <button class="btn btn-success">Lưu</button>
            </form>
        </div>
    </div>
</div>
@endsection
