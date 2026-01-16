@extends('users/master_layout')

@section('title')
<title>Mantu - Home Page</title>
@endsection

@section('content')
<!-- Main Content -->
<div class="mn-main-content">
	<div class="mn-breadcrumb m-b-30">
		<div class="row">
			<div class="col-12">
				<div class="row gi_breadcrumb_inner">
					<div class="col-md-6 col-sm-12">
						<h2 class="mn-breadcrumb-title">Trang cửa hàng</h2>
					</div>
					<div class="col-md-6 col-sm-12">
						<!-- mn-breadcrumb-list start -->
						<ul class="mn-breadcrumb-list">
							<li class="mn-breadcrumb-item"><a href="index.html">Trang chủ</a></li>
							<li class="mn-breadcrumb-item active">Cửa hàng</li>
						</ul>
						<!-- mn-breadcrumb-list end -->
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xxl-12">
			<!-- Shop section -->
			<section class="mn-shop padding-tb-30">
				<!-- Shop Banners Start -->

				<div class="row">
					<div class="mn-shop-rightside col-lg-9 col-md-12">
						<!-- Shop Top Start -->
						<div class="mn-pro-list-top d-flex">
							<div class="col-md-6 mn-grid-list">
								<div class="mn-gl-btn">
									<button class="grid-btn btn-grid active">
										<i class="ri-gallery-view-2"></i>
									</button>
									<button class="grid-btn btn-list">
										<i class="ri-list-check-2"></i>
									</button>
								</div>
							</div>
							<div class="col-md-6 mn-sort-select">
								<form method="GET" id="filterForm">
									<div class="mn-select-inner">
										<select name="sort" id="mn-select"
											onchange="document.getElementById('filterForm').submit()">

											<option value="">Sắp xếp theo</option>
											<option value="hot" {{ request('sort') == 'hot' ? 'selected' : '' }}>
												Sản phẩm bán chạy
											</option>
											<option value="new" {{ request('sort') == 'new' ? 'selected' : '' }}>
												Sản phẩm mới
											</option>
										</select>
									</div>

									{{-- Giữ brand khi sort --}}
									@foreach((array) request('brand', []) as $b)
									<input type="hidden" name="brand[]" value="{{ $b }}">
									@endforeach

									{{-- Giữ category khi sort --}}
									@foreach((array) request('category', []) as $c)
									<input type="hidden" name="category[]" value="{{ $c }}">
									@endforeach
								</form>
							</div>

						</div>
						<!-- Shop Top End -->

						<!-- Shop content Start -->
						<div class="shop-pro-content">
							<div class="shop-pro-inner">
								<div class="row">
									@foreach($products as $product)
									<div
										class="col-md-4 col-sm-6 col-xs-6 m-b-24 mn-product-box pro-gl-content">
										<div class="mn-product-card">
											<div class="mn-product-img">

												<div class="mn-img">
													<a href="product-detail.html" class="image">
														<img class="main-img" src="{{asset($product->images[0]->src ?? '')}}" alt="product">
														<img class="hover-img" src="{{asset($product->images[1]->src ?? '')}}" alt="product">
													</a>
													<div class="mn-pro-loader"></div>
													<div class="mn-options">
														<ul>
															<li><a href="javascript:void(0)" data-tooltip title="Add To Cart"
																	class="mn-add-cart"><i class="ri-shopping-cart-line"></i></a>
															</li>
														</ul>
													</div>
												</div>
											</div>
											<div class="mn-product-detail">
												<h5><a href="index.html">{{$product->name}}</a></h5>
												@php
												$unit = $product->units->first();
												@endphp
												<div class="mn-price">
													@if ($unit)
													<div class="mn-price-new">
														{{ number_format($unit->price_sale, 0, ',', '.') }}đ / {{ $unit->unit->name }}
													</div>
													@else
													<div class="text-muted">Chưa có giá</div>
													@endif
												</div>
											</div>
										</div>
									</div>
									@endforeach


								</div>
							</div>
							<!-- Pagination Start -->
							{{ $products->links()}}
							<!-- Pagination End -->
						</div>
						<!--Shop content End -->

					</div>
					<!-- Sidebar (replace your static blocks with this) -->
					<div class="mn-shop-sidebar col-lg-3 col-md-12 m-t-991">
						<div id="shop_sidebar">
							<div class="mn-sidebar-wrap">
								<form id="sidebarFilter" method="GET">
									<!-- Keep current sort in form -->
									<input type="hidden" name="sort" value="{{ request('sort') }}">

									<!-- Brands -->
									<div class="mn-sidebar-block">
										<div class="mn-sb-title">
											<h3 class="mn-sidebar-title">Thương hiệu</h3>
										</div>

										<div class="mn-sb-block-content">
											<ul>
												@foreach($brand as $b)
												<li>
													<div class="mn-sidebar-block-item">
														<input
															id="brand-{{ $b->id }}"
															type="checkbox"
															name="brand[]"
															value="{{ $b->id }}"
															{{ in_array($b->id, (array) request('brand', [])) ? 'checked' : '' }}
															class="filter-checkbox">
														<label for="brand-{{ $b->id }}">
															<a href="javascript:void(0)"><span>{{ $b->name }}</span></a>
														</label>
														<span class="checked"></span>
													</div>
												</li>
												@endforeach
											</ul>
										</div>
									</div>

									<!-- Categories -->
									<div class="mn-sidebar-block mt-3">
										<div class="mn-sb-title">
											<h3 class="mn-sidebar-title">Thể loại</h3>
										</div>

										<div class="mn-sb-block-content">
											<ul>
												@foreach($category as $cat)
												<li>
													<div class="mn-sidebar-block-item">
														<input
															id="cat-{{ $cat->id }}"
															type="checkbox"
															name="category[]"
															value="{{ $cat->id }}"
															{{ in_array($cat->id, (array) request('category', [])) ? 'checked' : '' }}
															class="filter-checkbox">
														<label for="cat-{{ $cat->id }}">
															<a href="javascript:void(0)"><span>{{ $cat->name }}</span></a>
														</label>
														<span class="checked"></span>
													</div>
												</li>
												@endforeach
											</ul>
										</div>
									</div>

									<!-- Optional: nút Áp dụng (nếu người dùng muốn nhấn thủ công) -->
									<div class="mt-3">
										<button type="submit" class="btn btn-primary btn-sm">Áp dụng</button>
										<a href="{{ route('shop') }}" class="btn btn-link btn-sm">Xóa bộ lọc</a>
									</div>
								</form>
							</div>
						</div>
					</div>

					<!-- Auto submit JS (place at bottom of page or in a scripts section) -->
					@push('scripts')
					<script>
						document.addEventListener('DOMContentLoaded', function() {
							const checkboxes = document.querySelectorAll('#sidebarFilter .filter-checkbox');

							checkboxes.forEach(cb => {
								cb.addEventListener('change', function() {
									// Nếu muốn debounce tránh submit quá nhiều lần, có thể thêm setTimeout
									document.getElementById('sidebarFilter').submit();
								});
							});
						});
					</script>
					@endpush

				</div>
			</section>
		</div>
	</div>
</div>
@endsection