@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold" style="color: #4B2067;">
                    <i class="bi bi-bell"></i> Thông báo
                </h2>
                <div>
                    @if($notifications->where('is_read', false)->count() > 0)
                        <form action="{{ route('notifications.markAllRead') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-check2-all"></i> Đánh dấu tất cả đã đọc
                            </button>
                        </form>
                    @endif
                    @if($notifications->where('is_read', true)->count() > 0)
                        <form action="{{ route('notifications.clearRead') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa tất cả thông báo đã đọc?')">
                                <i class="bi bi-trash"></i> Xóa đã đọc
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @forelse($notifications as $notification)
                <div class="card mb-3 {{ $notification->is_read ? 'bg-light' : 'border-primary' }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center mb-2">
                                    <h5 class="mb-0 {{ $notification->is_read ? 'text-muted' : 'fw-bold' }}">
                                        {{ $notification->title }}
                                    </h5>
                                    @if(!$notification->is_read)
                                        <span class="badge bg-primary ms-2">Mới</span>
                                    @endif
                                </div>
                                <p class="mb-2 {{ $notification->is_read ? 'text-muted' : '' }}">
                                    {{ $notification->message }}
                                </p>
                                <small class="text-muted">
                                    <i class="bi bi-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                </small>
                            </div>
                            <div class="ms-3">
                                @if($notification->link)
                                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> Xem
                                        </button>
                                    </form>
                                @elseif(!$notification->is_read)
                                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-check"></i> Đánh dấu đã đọc
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa thông báo này?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        @if($notification->type === 'order_status' && isset($notification->data['order_number']))
                            <div class="mt-2 p-2 bg-white rounded border">
                                <small class="text-muted">
                                    <strong>Mã đơn hàng:</strong> {{ $notification->data['order_number'] }}
                                </small>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-bell-slash" style="font-size: 4em; color: #ccc;"></i>
                    <p class="text-muted mt-3">Bạn chưa có thông báo nào</p>
                    <a href="{{ route('home.index') }}" class="btn btn-primary">
                        <i class="bi bi-house"></i> Về trang chủ
                    </a>
                </div>
            @endforelse

            @if($notifications->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
