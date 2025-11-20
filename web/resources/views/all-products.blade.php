@extends('layouts.app')
@section('content')
<div class="container mb-4" style="margin-top: 32px;">
	<div class="row">
		<!-- Sidebar bộ lọc -->
		<div class="col-md-3">
			<div class="bg-white rounded-3 shadow-sm p-3 mb-4">
				<h5 class="fw-bold mb-3" style="color: #4B2067;">NHÓM SẢN PHẨM</h5>
				<form method="GET" action="{{ route('products.all') }}" id="filterForm">
					<ul class="list-unstyled mb-3">
						<li class="mb-2">
							<a href="{{ route('products.all') }}" class="text-dark {{ !request('category_id') ? 'fw-bold' : '' }}">
								Tất Cả Nhóm Sản Phẩm
							</a>
						</li>
						@foreach($categories as $cat)
							<li class="mb-2">
								<a href="{{ route('products.all', ['category_id' => $cat->id]) }}" 
								   class="text-dark {{ request('category_id') == $cat->id ? 'fw-bold' : '' }}" 
								   style="{{ request('category_id') == $cat->id ? 'color: #F53003 !important;' : '' }}">
									{{ $cat->name }}
								</a>
							</li>
						@endforeach
					</ul>
					
					<h5 class="fw-bold mb-3 mt-4" style="color: #4B2067;">GIÁ</h5>
					<ul class="list-unstyled mb-3">
						<li class="mb-2">
							<label>
								<input type="radio" name="price_range" value="" {{ !request('price_range') ? 'checked' : '' }} onchange="this.form.submit()"> 
								Tất cả
							</label>
						</li>
						<li class="mb-2">
							<label>
								<input type="radio" name="price_range" value="1" {{ request('price_range') == '1' ? 'checked' : '' }} onchange="this.form.submit()"> 
								0đ - 150,000đ
							</label>
						</li>
						<li class="mb-2">
							<label>
								<input type="radio" name="price_range" value="2" {{ request('price_range') == '2' ? 'checked' : '' }} onchange="this.form.submit()"> 
								150,000đ - 300,000đ
							</label>
						</li>
						<li class="mb-2">
							<label>
								<input type="radio" name="price_range" value="3" {{ request('price_range') == '3' ? 'checked' : '' }} onchange="this.form.submit()"> 
								300,000đ - 500,000đ
							</label>
						</li>
						<li class="mb-2">
							<label>
								<input type="radio" name="price_range" value="4" {{ request('price_range') == '4' ? 'checked' : '' }} onchange="this.form.submit()"> 
								500,000đ - 700,000đ
							</label>
						</li>
						<li class="mb-2">
							<label>
								<input type="radio" name="price_range" value="5" {{ request('price_range') == '5' ? 'checked' : '' }} onchange="this.form.submit()"> 
								700,000đ - Trở Lên
							</label>
						</li>
					</ul>
					
					<h5 class="fw-bold mb-3 mt-4" style="color: #4B2067;">THỂ LOẠI</h5>
					<ul class="list-unstyled mb-3">
						<li class="mb-2">
							<label>
								<input type="radio" name="type" value="" {{ !request('type') ? 'checked' : '' }} onchange="this.form.submit()"> 
								Tất cả
							</label>
						</li>
						<li class="mb-2">
							<label>
								<input type="radio" name="type" value="Tiểu thuyết" {{ request('type') == 'Tiểu thuyết' ? 'checked' : '' }} onchange="this.form.submit()"> 
								Tiểu thuyết
							</label>
						</li>
						<li class="mb-2">
							<label>
								<input type="radio" name="type" value="Truyện ngắn" {{ request('type') == 'Truyện ngắn' ? 'checked' : '' }} onchange="this.form.submit()"> 
								Truyện ngắn
							</label>
						</li>
						<li class="mb-2">
							<label>
								<input type="radio" name="type" value="Light Novel" {{ request('type') == 'Light Novel' ? 'checked' : '' }} onchange="this.form.submit()"> 
								Light Novel
							</label>
						</li>
						<li class="mb-2">
							<label>
								<input type="radio" name="type" value="Trinh thám" {{ request('type') == 'Trinh thám' ? 'checked' : '' }} onchange="this.form.submit()"> 
								Trinh thám
							</label>
						</li>
						<li class="mb-2">
							<label>
								<input type="radio" name="type" value="Huyền bí" {{ request('type') == 'Huyền bí' ? 'checked' : '' }} onchange="this.form.submit()"> 
								Huyền bí
							</label>
						</li>
					</ul>
					
					<input type="hidden" name="category_id" value="{{ request('category_id') }}">
					<input type="hidden" name="sort" value="{{ request('sort') }}">
					<input type="hidden" name="per_page" value="{{ request('per_page') }}">
				</form>
			</div>
		</div>
		
		<!-- Grid sản phẩm -->
		<div class="col-md-9">
			<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
				<div class="fw-bold mb-2" style="color: #4B2067; font-size: 1.3em;">
					Tất cả sản phẩm ({{ $books->total() }})
				</div>
				<div class="d-flex gap-2">
					<form method="GET" action="{{ route('products.all') }}" class="d-flex gap-2 align-items-center">
						<input type="hidden" name="category_id" value="{{ request('category_id') }}">
						<input type="hidden" name="price_range" value="{{ request('price_range') }}">
						<input type="hidden" name="type" value="{{ request('type') }}">

						<select name="sort" class="form-select w-auto" onchange="this.form.submit()">
							<option value="">Bán Chạy Tuần</option>
							<option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới Nhất</option>
							<option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá Tăng Dần</option>
							<option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá Giảm Dần</option>
						</select>
						<select name="per_page" class="form-select w-auto" onchange="this.form.submit()">
							<option value="24" {{ request('per_page', 24) == 24 ? 'selected' : '' }}>24 sản phẩm</option>
							<option value="48" {{ request('per_page') == 48 ? 'selected' : '' }}>48 sản phẩm</option>
						</select>
					</form>
				</div>
			</div>
			
			<div class="row">
				@forelse ($books as $book)
					<div class="col-md-3 mb-4">
						<div class="card h-100 shadow-sm position-relative">
							<!-- Badge khuyến mãi -->
							@if ($book->price < 200000)
								<span class="position-absolute top-0 start-0 badge bg-danger" style="font-size: 0.85em; z-index: 10;">
									{{ $book->price < 100000 ? 'Bán chạy' : 'Xu hướng' }}
								</span>
							@endif
							@if($book->cover_path && file_exists(public_path('storage/' . $book->cover_path)))
								<img src="{{ asset('storage/' . $book->cover_path) }}" class="card-img-top" alt="{{ $book->title }}" style="height: 220px; object-fit: cover;">
							@else
								<img src="https://via.placeholder.com/150x220?text=No+Image" class="card-img-top" alt="{{ $book->title }}" style="height: 220px; object-fit: cover;">
							@endif
							<div class="card-body d-flex flex-column">
								<h6 class="card-title fw-bold" style="color: #4B2067; min-height: 40px; font-size: 0.95em;">{{ $book->title }}</h6>
								@if($book->type)
									<span class="badge bg-secondary mb-2" style="font-size: 0.75em; width: fit-content;">{{ $book->type }}</span>
								@endif
								<div class="mb-1 text-muted" style="font-size: 0.85em;">{{ $book->author->name ?? 'Không rõ' }}</div>
								<div class="fw-bold mb-2" style="color: #F53003; font-size: 1.05em;">
									{{ number_format($book->price, 0, ',', '.') }} đ
									@if ($book->price < 200000)
										<span class="badge bg-danger ms-1" style="font-size: 0.75em;">-15%</span>
									@endif
								</div>
								<!-- Rating -->
								<div class="mb-2">
									<span class="text-warning" style="font-size: 0.9em;">★★★★☆</span>
								</div>
								<div class="mt-auto">
									<a href="{{ route('book.detail', $book->id) }}" class="btn btn-outline-dark btn-sm w-100">Xem chi tiết</a>
								</div>
							</div>
						</div>
					</div>
				@empty
					<div class="col-12">
						<div class="alert alert-info">Chưa có sách nào.</div>
					</div>
				@endforelse
			</div>
			
			<div class="d-flex justify-content-center mt-4">
				{{ $books->appends(request()->query())->links() }}
			</div>
		</div>
	</div>
</div>
@endsection
