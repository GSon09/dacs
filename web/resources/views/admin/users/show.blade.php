@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Chi tiết người dùng</h1>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>Tên người dùng</th>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $user->email }}</td>
                </tr>
                <tr>
                    <th>Số điện thoại</th>
                    <td>{{ $user->phone ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Quyền</th>
                    <td>
                        @if($user->utype == 'ADM')
                            Quản trị viên
                        @else
                            Người dùng
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Ngày tạo</th>
                    <td>{{ $user->created_at }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection
