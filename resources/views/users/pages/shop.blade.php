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
						<div class="m-b-30">
							<div class="row">
								{{-- khi nào có dữ liệu đổi for thành foreach --}}
								{{-- @foreach($banners as $banner)  --}} 
								@for($i = 0; $i < 2; $i++)
									<div class="col-md-6">
										<div class="mn-ofr-banners">
											<div class="mn-bnr-body">
												<div class="mn-bnr-img">
													<a href="">
														{{-- <img src="{{ asset($banner->image) }}" alt="offer banner"> --}}
														<img src="{{ asset('uploads\banner\shop-banner.png') }}" alt="offer banner">
													</a>
												</div>
											</div>
										</div>
									</div>
									@endfor
								{{-- @endforeach --}}
							</div>
						</div>
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
										<div class="mn-select-inner">
											<select name="mn-select" id="mn-select">
												<option selected disabled>Sort by</option>
												<option value="1">Position</option>
												<option value="2">Relevance</option>
												<option value="3">Name, A to Z</option>
												<option value="4">Name, Z to A</option>
												<option value="5">Price, low to high</option>
												<option value="6">Price, high to low</option>
											</select>
										</div>
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
															<div class="lbl">
																<span class="new">new</span>
															</div>
															<div class="mn-img">
																<a href="product-detail.html" class="image">
																	<img class="main-img" src="{{asset($product->images[0]->src ?? '')}}" alt="product">
																	<img class="hover-img" src="{{asset($product->images[1]->src ?? '')}}" alt="product">
																</a>
																<div class="mn-pro-loader"></div>
																<div class="mn-options">
																	<ul>
																		<li><a href="javascript:void(0)"><i
																					class="ri-eye-line"></i></a></li>
																		<li><a href="javascript:void(0)" data-tooltip title="Compare"
																				class="mn-compare"><i class="ri-repeat-line"></i></a></li>
																		<li><a href="javascript:void(0)" data-tooltip title="Add To Cart"
																				class="mn-add-cart"><i class="ri-shopping-cart-line"></i></a>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
														<div class="mn-product-detail">
															<h5><a href="index.html">Cotton fabric T-shirt</a></h5>
															<div class="mn-price">
																<div class="mn-price-new">$120</div>
																<div class="mn-price-old">$130</div>
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
							<!-- Sidebar Area Start -->
							<div class="mn-shop-sidebar col-lg-3 col-md-12 m-t-991">
								<div id="shop_sidebar">
									<div class="mn-sidebar-wrap">
										<!-- Sidebar Brand Block -->
										<div class="mn-sidebar-block">
											<div class="mn-sb-title">
												<h3 class="mn-sidebar-title">Thương hiệu</h3>
											</div>
											<div class="mn-sb-block-content">
												<ul>
													<li>
														<div class="mn-sidebar-block-item">
															<input type="checkbox" checked>
															<a href="javascript:void(0)">
																<span>Zencart Mart</span>
															</a>
															<span class="checked"></span>
														</div>
													</li>
													<li>
														<div class="mn-sidebar-block-item">
															<input type="checkbox">
															<a href="javascript:void(0)">
																<span>Xeta Store</span>
															</a>
															<span class="checked"></span>
														</div>
													</li>
													<li>
														<div class="mn-sidebar-block-item">
															<input type="checkbox">
															<a href="javascript:void(0)">
																<span>Pili Market</span>
															</a>
															<span class="checked"></span>
														</div>
													</li>
													<li>
														<div class="mn-sidebar-block-item">
															<input type="checkbox">
															<a href="javascript:void(0)">
																<span>Indiana Store</span>
															</a>
															<span class="checked"></span>
														</div>
													</li>
												</ul>
											</div>
										</div>
										<!-- Sidebar Price Block -->
										<div class="mn-sidebar-block">
											<div class="mn-sb-title">
												<h3 class="mn-sidebar-title">Giá</h3>
											</div>
											<div class="mn-sb-block-content mn-price-range-slider es-price-slider">
												<div class="mn-price-filter">
													<div class="mn-price-input">
														<label class="filter__label">
															Từ<input type="text" class="filter__input">
														</label>
														<span class="mn-price-divider"></span>
														<label class="filter__label">
															đến<input type="text" class="filter__input">
														</label>
													</div>
													<div id="mn-sliderPrice" class="filter__slider-price" data-min="0"
														data-max="250" data-step="10"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
@endsection