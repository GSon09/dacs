@extends('layouts.app')
@section('content')
        <main>
            <div class="container mb-4" style="margin-top: 32px;">
               
                <div class="row g-3 mb-3">
                    <div class="col-lg-8 d-flex flex-column justify-content-between">
                        <div id="mainBannerSlider" class="carousel slide rounded-3 overflow-hidden w-100" data-bs-ride="carousel" style="aspect-ratio: 3/1; background: #eaeaea;">
                            <div class="carousel-inner" style="aspect-ratio: 3/1;">
                                <div class="carousel-item active">
                                    <img src="https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=1200&q=80" alt="Banner 1" class="d-block w-100 h-100" style="object-fit: cover; aspect-ratio: 3/1;">
                                </div>
                                <div class="carousel-item">
                                    <img src="https://images.unsplash.com/photo-1519125323398-675f0ddb6308?auto=format&fit=crop&w=1200&q=80" alt="Banner 2" class="d-block w-100 h-100" style="object-fit: cover; aspect-ratio: 3/1;">
                                </div>
                                <div class="carousel-item">
                                    <img src="https://images.unsplash.com/photo-1516979187457-637abb4f9353?auto=format&fit=crop&w=1200&q=80" alt="Banner 3" class="d-block w-100 h-100" style="object-fit: cover; aspect-ratio: 3/1;">
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#mainBannerSlider" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#mainBannerSlider" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-4 d-flex flex-column gap-3">
                        <div class="rounded-3 overflow-hidden w-100" style="aspect-ratio: 3/1; background: #f3e9fa;">
                            <img src="https://images.unsplash.com/photo-1516979187457-637abb4f9353?auto=format&fit=crop&w=600&q=80" alt="Banner nhỏ 1" class="w-100 h-100" style="object-fit: cover; aspect-ratio: 3/1;">
                        </div>
                        <div class="rounded-3 overflow-hidden w-100" style="aspect-ratio: 3/1; background: #f3e9fa;">
                            <img src="https://images.unsplash.com/photo-1519125323398-675f0ddb6308?auto=format&fit=crop&w=600&q=80" alt="Banner nhỏ 2" class="w-100 h-100" style="object-fit: cover; aspect-ratio: 3/1;">
                        </div>
                    </div>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="rounded-3 overflow-hidden w-100" style="aspect-ratio: 5/3; background: #fff7e6;">
                            <img src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&w=600&q=80" alt="Banner nhỏ 3" class="w-100 h-100" style="object-fit: cover; aspect-ratio: 4/3;">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="rounded-3 overflow-hidden w-100" style="aspect-ratio: 5/3; background: #e6f7ff;">
                            <img src="https://images.unsplash.com/photo-1524985069026-dd778a71c7b4?auto=format&fit=crop&w=600&q=80" alt="Banner nhỏ 4" class="w-100 h-100" style="object-fit: cover; aspect-ratio: 4/3;">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="rounded-3 overflow-hidden w-100" style="aspect-ratio: 5/3; background: #f9e6ff;">
                            <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=600&q=80" alt="Banner nhỏ 5" class="w-100 h-100" style="object-fit: cover; aspect-ratio: 4/3;">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="rounded-3 overflow-hidden w-100" style="aspect-ratio: 5/3; background: #e6ffe6;">
                            <img src="https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=600&q=80" alt="Banner nhỏ 6" class="w-100 h-100" style="object-fit: cover; aspect-ratio: 4/3;">
                        </div>
                    </div>
                </div>
                <!-- Category section -->
                <div class="mb-3">
                    <h4 class="fw-bold" style="color: #4B2067;">Danh mục nổi bật</h4>
                </div>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card text-center border-0 shadow-sm">
                            <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794" class="card-img-top" alt="Văn học" style="height: 120px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title" style="color: #4B2067;">Văn học</h5>
                                <a href="{{ route('category.show', ['id' => 1]) }}" class="btn btn-outline-dark btn-sm">Xem sách</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center border-0 shadow-sm">
                            <img src="https://images.unsplash.com/photo-1524985069026-dd778a71c7b4" class="card-img-top" alt="Kỹ năng sống" style="height: 120px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title" style="color: #4B2067;">Kỹ năng sống</h5>
                                <a href="{{ route('category.show', ['id' => 3]) }}" class="btn btn-outline-dark btn-sm">Xem sách</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center border-0 shadow-sm">
                            <img src="https://images.unsplash.com/photo-1465101046530-73398c7f28ca" class="card-img-top" alt="Thiếu nhi" style="height: 120px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title" style="color: #4B2067;">Thiếu nhi</h5>
                                <a href="{{ route('category.show', ['id' => 4]) }}" class="btn btn-outline-dark btn-sm">Xem sách</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ...existing code... -->
                <!-- Shop info & Contact section -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="p-4 bg-light rounded-3 shadow-sm h-100">
                            <h5 class="fw-bold mb-2" style="color: #4B2067;">Thông tin cửa hàng</h5>
                            <p>Địa chỉ: Phenikaa Uni<br>Giờ mở cửa: 8:00 - 21:00<br>Hotline: 0123 456 789</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-4 bg-light rounded-3 shadow-sm h-100">
                            <h5 class="fw-bold mb-2" style="color: #4B2067;">Liên hệ & Dịch vụ</h5>
                            <p>Email: support@huvosach.vn<br>Fanpage: fb.com/huvosach<br>Chăm sóc khách hàng, đổi trả, vận chuyển.</p>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="bg-light border-top py-4 mt-5">
                <div class="container text-center">
                    <div class="mb-2">
                        <a href="#" class="mx-2 text-danger">Chính sách bảo mật</a> |
                        <a href="#" class="mx-2 text-danger">Điều khoản sử dụng</a> |
                        <a href="#" class="mx-2 text-danger">Liên hệ</a>
                    </div>
                    <div class="mb-2">
                        <a href="#" class="mx-2"><img src="https://cdn1.fahasa.com/media/wysiwyg/Logo-NCC/logo_lex.jpg" alt="Logo đối tác" style="height:32px;"></a>
                        <a href="#" class="mx-2"><img src="https://cdn1.fahasa.com/media/wysiwyg/Logo-NCC/Logo_ninjavan.png" alt="Logo đối tác" style="height:32px;"></a>
                        <a href="#" class="mx-2"><img src="https://cdn1.fahasa.com/media/wysiwyg/Logo-NCC/vnpost1.png" alt="Logo đối tác" style="height:32px;"></a>
                    </div>
                    <div class="text-secondary">&copy; {{ date('Y') }} Tiệm sách Hư vô. All rights reserved.</div>
                </div>
            </footer>
        </main>
@endsection
