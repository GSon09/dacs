@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-lg">
    <h2 class="mb-4">Tài khoản của tôi</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->has('general'))
        <div class="alert alert-danger">{{ $errors->first('general') }}</div>
    @endif

    <form method="POST" action="{{ route('user.account.update') }}">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="name">Họ và tên</label>
            <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required maxlength="100">
            @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="form-group mb-3">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" class="form-control" value="{{ $user->email }}" readonly>
            <small class="text-muted">Email không được phép thay đổi.</small>
        </div>

        <div class="form-group mb-3">
            <label for="phone">Số điện thoại</label>
            <input id="phone" name="phone" type="text" class="form-control" value="{{ old('phone', $user->phone) }}" pattern="[0-9]{9,15}">
            @error('phone') <div class="text-danger small">{{ $message }}</div> @enderror
            <div class="mt-2">
                <form method="POST" action="{{ route('user.account.sendOtp') }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="phone" value="{{ old('phone', $user->phone) }}">
                    <button type="submit" class="btn btn-secondary btn-sm">Gửi mã OTP tới email</button>
                </form>
            </div>
        </div>

        <div class="form-group mb-3">
            <label for="address">Địa chỉ</label>
            <input id="address" name="address" type="text" class="form-control" value="{{ old('address', $user->address) }}" maxlength="255">
            @error('address') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
    </form>

    <hr />

    <h4>Hoàn tất xác thực SĐT (nếu đã nhận mã)</h4>
    <form method="POST" action="{{ route('user.account.verifyOtp') }}">
        @csrf
        <div class="form-group mb-2">
            <label for="otp_phone">Số điện thoại</label>
            <input id="otp_phone" name="phone" type="text" class="form-control" value="{{ old('phone', $user->phone) }}" required>
        </div>
        <div class="form-group mb-2">
            <label for="code">Mã xác thực (6 chữ số)</label>
            <input id="code" name="code" type="text" class="form-control" maxlength="6" required>
        </div>
        <button type="submit" class="btn btn-success">Xác thực và cập nhật SĐT</button>
    </form>
</div>

@endsection
