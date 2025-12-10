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
								<h2 class="mn-breadcrumb-title">Shop Page</h2>
							</div>
							<div class="col-md-6 col-sm-12">
								<!-- mn-breadcrumb-list start -->
								<ul class="mn-breadcrumb-list">
									<li class="mn-breadcrumb-item"><a href="index.html">Home</a></li>
									<li class="mn-breadcrumb-item active">Shop Page</li>
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
								<div class="col-md-6">
									<div class="mn-ofr-banners">
										<div class="mn-bnr-body">
											<div class="mn-bnr-img">
												<span class="lbl">70% Off</span>
												<img src="assets/img/banner/5.jpg" alt="banner">
											</div>
											<div class="mn-bnr-detail">
												<h5>Best men's fashion sale</h5>
												<p>Stylish Design of clothes.</p>
												<a href="shop-right-sidebar.html" class="mn-btn-2"><span>Shop
														Now</span></a>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="mn-ofr-banners m-t-767">
										<div class="mn-bnr-body">
											<div class="mn-bnr-img">
												<span class="lbl">50% Off</span>
												<img src="assets/img/banner/6.jpg" alt="banner">
											</div>
											<div class="mn-bnr-detail">
												<h5>Trending women's sale</h5>
												<p>Trending desings of clothes.</p>
												<a href="shop-right-sidebar.html" class="mn-btn-2"><span>Shop
														Now</span></a>
											</div>
										</div>
									</div>
								</div>
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

								<!-- Select Bar Start -->
								<div class="mn-select-bar d-flex">
									<span class="mn-select-btn">Clothes<a class="mn-select-cancel"
											href="javascript:void(0)">×</a></span>
									<span class="mn-select-btn">Fruits<a class="mn-select-cancel"
											href="javascript:void(0)">×</a></span>
									<span class="mn-select-btn">Snacks<a class="mn-select-cancel"
											href="javascript:void(0)">×</a></span>
									<span class="mn-select-btn">Dairy<a class="mn-select-cancel"
											href="javascript:void(0)">×</a></span>
									<span class="mn-select-btn">perfume<a class="mn-select-cancel"
											href="javascript:void(0)">×</a></span>
									<span class="mn-select-btn">jewelry<a class="mn-select-cancel"
											href="javascript:void(0)">×</a></span>
									<span class="mn-select-btn mn-select-btn-clear"><a class="mn-select-clear"
											href="javascript:void(0)">Clear All</a></span>
								</div>
								<!-- Select Bar End -->

								<!-- Shop content Start -->
								<div class="shop-pro-content">
									<div class="shop-pro-inner">
										<div class="row">
											<div
												class="col-md-4 col-sm-6 col-xs-6 m-b-24 mn-product-box pro-gl-content">
												<div class="mn-product-card">
													<div class="mn-product-img">
														<div class="lbl">
															<span class="new">new</span>
														</div>
														<div class="mn-img">
															<a href="product-detail.html" class="image">
																<img class="main-img" src="assets/img/product/17.jpg"
																	alt="product">
																<img class="hover-img" src="assets/img/product/18.jpg"
																	alt="product">
															</a>
															<div class="mn-pro-loader"></div>
															<div class="mn-options">
																<ul>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Quick View"
																			data-link-action="quickview"
																			data-bs-toggle="modal"
																			data-bs-target="#quickview_modal"><i
																				class="ri-eye-line"></i></a></li>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Compare" class="mn-compare"><i
																				class="ri-repeat-line"></i></a></li>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Add To Cart" class="mn-add-cart"><i
																				class="ri-shopping-cart-line"></i></a>
																	</li>
																</ul>
															</div>
														</div>
													</div>
													<div class="mn-product-detail">
														<div class="cat">
															<a href="shop-right-sidebar.html">T-shirt</a>
															<ul>
																<li>s</li>
																<li>m</li>
																<li>xl</li>
															</ul>
														</div>
														<h5><a href="shop-banner-right-sidebar.html">Cotton fabric T-shirt</a></h5>
														<p class="mn-info">Lorem Ipsum is simply dummy text of the
															printing and typesetting industry. Lorem Ipsum has been the
															industry's standard dummy text ever since the 1500s.</p>
														<div class="mn-price">
															<div class="mn-price-new">$120</div>
															<div class="mn-price-old">$130</div>
														</div>
														<div class="mn-pro-option">
															<div class="mn-pro-color">
																<ul class="mn-opt-swatch mn-change-img">
																	<li><a href="shop-banner-right-sidebar.html#" class="mn-opt-clr-img"
																			data-src="assets/img/product/19.jpg"
																			data-src-hover="assets/img/product/20.jpg"
																			data-tooltip="Orange"><span
																				style="background-color:#de8abc;"></span></a>
																	</li>
																	<li><a href="shop-banner-right-sidebar.html#" class="mn-opt-clr-img"
																			data-src="assets/img/product/21.jpg"
																			data-src-hover="assets/img/product/22.jpg"
																			data-tooltip="Orange"><span
																				style="background-color:#5e68ce;"></span></a>
																	</li>
																	<li><a href="shop-banner-right-sidebar.html#" class="mn-opt-clr-img"
																			data-src="assets/img/product/17.jpg"
																			data-src-hover="assets/img/product/18.jpg"
																			data-tooltip="Orange"><span
																				style="background-color:#eee;"></span></a>
																	</li>
																</ul>
															</div>
															<a href="javascript:void(0);" class="mn-wishlist"
																data-tooltip title="Wishlist">
																<i class="ri-heart-line"></i>
															</a>
														</div>
													</div>
												</div>
											</div>
											<div
												class="col-md-4 col-sm-6 col-xs-6 m-b-24 mn-product-box pro-gl-content">
												<div class="mn-product-card">
													<div class="mn-product-img">
														<div class="lbl">
															<span class="sale">Sale</span>
														</div>
														<div class="mn-img">
															<a href="product-detail.html" class="image">
																<img class="main-img" src="assets/img/product/39.jpg"
																	alt="product">
																<img class="hover-img" src="assets/img/product/40.jpg"
																	alt="product">
															</a>
															<div class="mn-pro-loader"></div>
															<div class="mn-options">
																<ul>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Quick View"
																			data-link-action="quickview"
																			data-bs-toggle="modal"
																			data-bs-target="#quickview_modal"><i
																				class="ri-eye-line"></i></a></li>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Compare" class="mn-compare"><i
																				class="ri-repeat-line"></i></a></li>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Add To Cart" class="mn-add-cart"><i
																				class="ri-shopping-cart-line"></i></a>
																	</li>
																</ul>
															</div>
														</div>
													</div>
													<div class="mn-product-detail">
														<div class="cat">
															<a href="shop-right-sidebar.html">T-shirt</a>
															<ul>
																<li>s</li>
																<li>l</li>
															</ul>
														</div>
														<h5><a href="shop-banner-right-sidebar.html">Leather purse</a></h5>
														<p class="mn-info">Lorem Ipsum is simply dummy text of the
															printing and typesetting industry. Lorem Ipsum has been the
															industry's standard dummy text ever since the 1500s.</p>
														<div class="mn-price">
															<div class="mn-price-new">$90</div>
															<div class="mn-price-old">$95</div>
														</div>
														<div class="mn-pro-option">
															<div class="mn-pro-color">
																<ul class="mn-opt-swatch mn-change-img">
																	<li><a href="shop-banner-right-sidebar.html#" class="mn-opt-clr-img"
																			data-src="assets/img/product/39.jpg"
																			data-src-hover="assets/img/product/39.jpg"
																			data-tooltip="Orange"><span
																				style="background-color:#cfa394;"></span></a>
																	</li>
																	<li><a href="shop-banner-right-sidebar.html#" class="mn-opt-clr-img"
																			data-src="assets/img/product/40.jpg"
																			data-src-hover="assets/img/product/40.jpg"
																			data-tooltip="Orange"><span
																				style="background-color:#705a82;"></span></a>
																	</li>
																</ul>
															</div>
															<a href="javascript:void(0);" class="mn-wishlist"
																data-tooltip title="Wishlist">
																<i class="ri-heart-line"></i>
															</a>
														</div>
													</div>
												</div>
											</div>
											<div
												class="col-md-4 col-sm-6 col-xs-6 m-b-24 mn-product-box pro-gl-content">
												<div class="mn-product-card">
													<div class="mn-product-img">
														<div class="lbl">
															<span class="trending">trending</span>
														</div>
														<div class="mn-img">
															<a href="product-detail.html" class="image">
																<img class="main-img" src="assets/img/product/5.jpg"
																	alt="product">
																<img class="hover-img" src="assets/img/product/6.jpg"
																	alt="product">
															</a>
															<div class="mn-pro-loader"></div>
															<div class="mn-options">
																<ul>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Quick View"
																			data-link-action="quickview"
																			data-bs-toggle="modal"
																			data-bs-target="#quickview_modal"><i
																				class="ri-eye-line"></i></a></li>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Compare" class="mn-compare"><i
																				class="ri-repeat-line"></i></a></li>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Add To Cart" class="mn-add-cart"><i
																				class="ri-shopping-cart-line"></i></a>
																	</li>
																</ul>
															</div>
														</div>
													</div>
													<div class="mn-product-detail">
														<div class="cat">
															<a href="shop-right-sidebar.html">T-shirt</a>
															<ul>
																<li>s</li>
																<li>m</li>
																<li>xl</li>
															</ul>
														</div>
														<h5><a href="shop-banner-right-sidebar.html">Cotton fabric T-shirt</a></h5>
														<p class="mn-info">Lorem Ipsum is simply dummy text of the
															printing and typesetting industry. Lorem Ipsum has been the
															industry's standard dummy text ever since the 1500s.</p>
														<div class="mn-price">
															<div class="mn-price-new">$120</div>
															<div class="mn-price-old">$130</div>
														</div>
														<div class="mn-pro-option">
															<div class="mn-pro-color">
																<ul class="mn-opt-swatch mn-change-img">
																	<li class="active"><a href="shop-banner-right-sidebar.html#"
																			class="mn-opt-clr-img active"
																			data-src="assets/img/product/5.jpg"
																			data-src-hover="assets/img/product/5.jpg"
																			data-tooltip="Gray"><span
																				style="background-image:url('assets/img/product/bg/5.jpg');"></span></a>
																	</li>
																	<li><a href="shop-banner-right-sidebar.html#" class="mn-opt-clr-img"
																			data-src="assets/img/product/6.jpg"
																			data-src-hover="assets/img/product/6.jpg"
																			data-tooltip="Orange"><span
																				style="background-image:url('assets/img/product/bg/6.jpg');"></span></a>
																	</li>
																	<li><a href="shop-banner-right-sidebar.html#" class="mn-opt-clr-img"
																			data-src="assets/img/product/7.jpg"
																			data-src-hover="assets/img/product/7.jpg"
																			data-tooltip="Orange"><span
																				style="background-color:#e97e70;"></span></a>
																	</li>
																	<li><a href="shop-banner-right-sidebar.html#" class="mn-opt-clr-img"
																			data-src="assets/img/product/8.jpg"
																			data-src-hover="assets/img/product/8.jpg"
																			data-tooltip="Orange"><span
																				style="background-color:#70e98a;"></span></a>
																	</li>
																</ul>
															</div>
															<a href="javascript:void(0);" class="mn-wishlist"
																data-tooltip title="Wishlist">
																<i class="ri-heart-line"></i>
															</a>
														</div>
													</div>
												</div>
											</div>
											<div
												class="col-md-4 col-sm-6 col-xs-6 m-b-24 mn-product-box pro-gl-content">
												<div class="mn-product-card">
													<div class="mn-product-img">
														<div class="mn-img">
															<a href="product-detail.html" class="image">
																<img class="main-img" src="assets/img/product/9.jpg"
																	alt="product">
																<img class="hover-img" src="assets/img/product/10.jpg"
																	alt="product">
															</a>
															<div class="mn-pro-loader"></div>
															<div class="mn-options">
																<ul>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Quick View"
																			data-link-action="quickview"
																			data-bs-toggle="modal"
																			data-bs-target="#quickview_modal"><i
																				class="ri-eye-line"></i></a></li>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Compare" class="mn-compare"><i
																				class="ri-repeat-line"></i></a></li>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Add To Cart" class="mn-add-cart"><i
																				class="ri-shopping-cart-line"></i></a>
																	</li>
																</ul>
															</div>
														</div>
													</div>
													<div class="mn-product-detail">
														<div class="cat">
															<a href="shop-right-sidebar.html">Shoes</a>
															<ul>
																<li>7</li>
																<li>8</li>
																<li>10</li>
															</ul>
														</div>
														<h5><a href="shop-banner-right-sidebar.html">Special sport shoes</a></h5>
														<p class="mn-info">Lorem Ipsum is simply dummy text of the
															printing and typesetting industry. Lorem Ipsum has been the
															industry's standard dummy text ever since the 1500s.</p>
														<div class="mn-price">
															<div class="mn-price-new">$55</div>
														</div>
														<div class="mn-pro-option">
															<div class="mn-pro-color">
																<ul class="mn-opt-swatch mn-change-img">
																	<li><a href="shop-banner-right-sidebar.html#" class="mn-opt-clr-img"
																			data-src="assets/img/product/9.jpg"
																			data-src-hover="assets/img/product/9.jpg"
																			data-tooltip="Orange"><span
																				style="background-color:#0e0e0e;"></span></a>
																	</li>
																	<li><a href="shop-banner-right-sidebar.html#" class="mn-opt-clr-img"
																			data-src="assets/img/product/10.jpg"
																			data-src-hover="assets/img/product/10.jpg"
																			data-tooltip="Orange"><span
																				style="background-color:#c54367;"></span></a>
																	</li>
																</ul>
															</div>
															<a href="javascript:void(0);" class="mn-wishlist active"
																data-tooltip title="Wishlist">
																<i class="ri-heart-line"></i>
															</a>
														</div>
													</div>
												</div>
											</div>
											<div
												class="col-md-4 col-sm-6 col-xs-6 m-b-24 mn-product-box pro-gl-content">
												<div class="mn-product-card">
													<div class="mn-product-img">
														<div class="lbl">
															<span class="new">new</span>
														</div>
														<div class="mn-img">
															<a href="product-detail.html" class="image">
																<img class="main-img" src="assets/img/product/1.jpg"
																	alt="product">
																<img class="hover-img" src="assets/img/product/3.jpg"
																	alt="product">
															</a>
															<div class="mn-pro-loader"></div>
															<div class="mn-options">
																<ul>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Quick View"
																			data-link-action="quickview"
																			data-bs-toggle="modal"
																			data-bs-target="#quickview_modal"><i
																				class="ri-eye-line"></i></a></li>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Compare" class="mn-compare"><i
																				class="ri-repeat-line"></i></a></li>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Add To Cart" class="mn-add-cart"><i
																				class="ri-shopping-cart-line"></i></a>
																	</li>
																</ul>
															</div>
														</div>
													</div>
													<div class="mn-product-detail">
														<div class="cat">
															<a href="shop-right-sidebar.html">Top</a>
															<ul>
																<li>s</li>
																<li>m</li>
															</ul>
														</div>
														<h5><a href="shop-banner-right-sidebar.html">Cotton fabric Top</a></h5>
														<p class="mn-info">Lorem Ipsum is simply dummy text of the
															printing and typesetting industry. Lorem Ipsum has been the
															industry's standard dummy text ever since the 1500s.</p>
														<div class="mn-price">
															<div class="mn-price-new">$120</div>
															<div class="mn-price-old">$130</div>
														</div>
														<div class="mn-pro-option">
															<div class="mn-pro-color">
																<ul class="mn-opt-swatch mn-change-img">
																	<li class="active"><a href="shop-banner-right-sidebar.html#"
																			class="mn-opt-clr-img active"
																			data-src="assets/img/product/1.jpg"
																			data-src-hover="assets/img/product/3.jpg"
																			data-tooltip="Gray"><span
																				style="background-color:#f3f3f3;"></span></a>
																	</li>
																	<li><a href="shop-banner-right-sidebar.html#" class="mn-opt-clr-img"
																			data-src="assets/img/product/2.jpg"
																			data-src-hover="assets/img/product/4.jpg"
																			data-tooltip="Orange"><span
																				style="background-color:#e8c2ff;"></span></a>
																	</li>
																</ul>
															</div>
															<a href="javascript:void(0);" class="mn-wishlist"
																data-tooltip title="Wishlist">
																<i class="ri-heart-line"></i>
															</a>
														</div>
													</div>
												</div>
											</div>
											<div
												class="col-md-4 col-sm-6 col-xs-6 m-b-24 mn-product-box pro-gl-content">
												<div class="mn-product-card">
													<div class="mn-product-img">
														<div class="lbl">
															<span class="sale">sale</span>
														</div>
														<div class="mn-img">
															<a href="product-detail.html" class="image">
																<img class="main-img" src="assets/img/product/11.jpg"
																	alt="product">
																<img class="hover-img" src="assets/img/product/12.jpg"
																	alt="product">
															</a>
															<div class="mn-pro-loader"></div>
															<div class="mn-options">
																<ul>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Quick View"
																			data-link-action="quickview"
																			data-bs-toggle="modal"
																			data-bs-target="#quickview_modal"><i
																				class="ri-eye-line"></i></a></li>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Compare" class="mn-compare"><i
																				class="ri-repeat-line"></i></a></li>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Add To Cart" class="mn-add-cart"><i
																				class="ri-shopping-cart-line"></i></a>
																	</li>
																</ul>
															</div>
														</div>
													</div>
													<div class="mn-product-detail">
														<div class="cat">
															<a href="shop-right-sidebar.html">watches</a>
														</div>
														<h5><a href="shop-banner-right-sidebar.html">Mantu smart watch</a></h5>
														<p class="mn-info">Lorem Ipsum is simply dummy text of the
															printing and typesetting industry. Lorem Ipsum has been the
															industry's standard dummy text ever since the 1500s.</p>
														<div class="mn-price">
															<div class="mn-price-new">$955</div>
															<div class="mn-price-old">$999</div>
														</div>
														<div class="mn-pro-option">
															<div class="mn-pro-color">
																<ul class="mn-opt-swatch mn-change-img">
																	<li><a href="shop-banner-right-sidebar.html#" class="mn-opt-clr-img"
																			data-src="assets/img/product/11.jpg"
																			data-src-hover="assets/img/product/12.jpg"
																			data-tooltip="Orange"><span
																				style="background-color:#f3f3f3;"></span></a>
																	</li>
																	<li><a href="shop-banner-right-sidebar.html#" class="mn-opt-clr-img"
																			data-src="assets/img/product/12.jpg"
																			data-src-hover="assets/img/product/11.jpg"
																			data-tooltip="Orange"><span
																				style="background-color:#242424;"></span></a>
																	</li>
																</ul>
															</div>
															<a href="javascript:void(0);" class="mn-wishlist"
																data-tooltip title="Wishlist">
																<i class="ri-heart-line"></i>
															</a>
														</div>
													</div>
												</div>
											</div>
											<div
												class="col-md-4 col-sm-6 col-xs-6 m-b-24 mn-product-box pro-gl-content">
												<div class="mn-product-card">
													<div class="mn-product-img">
														<div class="lbl">
															<span class="discount">20% off</span>
														</div>
														<div class="mn-img">
															<a href="product-detail.html" class="image">
																<img class="main-img" src="assets/img/product/13.jpg"
																	alt="product">
																<img class="hover-img" src="assets/img/product/14.jpg"
																	alt="product">
															</a>
															<div class="mn-pro-loader"></div>
															<div class="mn-options">
																<ul>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Quick View"
																			data-link-action="quickview"
																			data-bs-toggle="modal"
																			data-bs-target="#quickview_modal"><i
																				class="ri-eye-line"></i></a></li>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Compare" class="mn-compare"><i
																				class="ri-repeat-line"></i></a></li>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Add To Cart" class="mn-add-cart"><i
																				class="ri-shopping-cart-line"></i></a>
																	</li>
																</ul>
															</div>
														</div>
													</div>
													<div class="mn-product-detail">
														<div class="cat">
															<a href="shop-right-sidebar.html">belt</a>
														</div>
														<h5><a href="shop-banner-right-sidebar.html">Mantu leather belt</a></h5>
														<p class="mn-info">Lorem Ipsum is simply dummy text of the
															printing and typesetting industry. Lorem Ipsum has been the
															industry's standard dummy text ever since the 1500s.</p>
														<div class="mn-price">
															<div class="mn-price-new">$10</div>
															<div class="mn-price-old">$12</div>
														</div>
														<div class="mn-pro-option">
															<div class="mn-pro-color">
																<ul class="mn-opt-swatch mn-change-img">
																	<li><a href="shop-banner-right-sidebar.html#" class="mn-opt-clr-img"
																			data-src="assets/img/product/13.jpg"
																			data-src-hover="assets/img/product/14.jpg"
																			data-tooltip="Orange"><span
																				style="background-color:#d48a5b;"></span></a>
																	</li>
																	<li><a href="shop-banner-right-sidebar.html#" class="mn-opt-clr-img"
																			data-src="assets/img/product/14.jpg"
																			data-src-hover="assets/img/product/13.jpg"
																			data-tooltip="Orange"><span
																				style="background-color:#242424;"></span></a>
																	</li>
																</ul>
															</div>
															<a href="javascript:void(0);" class="mn-wishlist"
																data-tooltip title="Wishlist">
																<i class="ri-heart-line"></i>
															</a>
														</div>
													</div>
												</div>
											</div>
											<div
												class="col-md-4 col-sm-6 col-xs-6 m-b-24 mn-product-box pro-gl-content">
												<div class="mn-product-card">
													<div class="mn-product-img">
														<div class="mn-img">
															<a href="product-detail.html" class="image">
																<img class="main-img" src="assets/img/product/15.jpg"
																	alt="product">
																<img class="hover-img" src="assets/img/product/16.jpg"
																	alt="product">
															</a>
															<div class="mn-pro-loader"></div>
															<div class="mn-options">
																<ul>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Quick View"
																			data-link-action="quickview"
																			data-bs-toggle="modal"
																			data-bs-target="#quickview_modal"><i
																				class="ri-eye-line"></i></a></li>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Compare" class="mn-compare"><i
																				class="ri-repeat-line"></i></a></li>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Add To Cart" class="mn-add-cart"><i
																				class="ri-shopping-cart-line"></i></a>
																	</li>
																</ul>
															</div>
														</div>
													</div>
													<div class="mn-product-detail">
														<div class="cat">
															<a href="shop-right-sidebar.html">Bag</a>
															<ul>
																<li>m</li>
																<li>l</li>
															</ul>
														</div>
														<h5><a href="shop-banner-right-sidebar.html">Leather bag</a></h5>
														<p class="mn-info">Lorem Ipsum is simply dummy text of the
															printing and typesetting industry. Lorem Ipsum has been the
															industry's standard dummy text ever since the 1500s.</p>
														<div class="mn-price">
															<div class="mn-price-new">$66</div>
														</div>
														<div class="mn-pro-option">
															<div class="mn-pro-color">
																<ul class="mn-opt-swatch mn-change-img">
																	<li><a href="shop-banner-right-sidebar.html#" class="mn-opt-clr-img"
																			data-src="assets/img/product/15.jpg"
																			data-src-hover="assets/img/product/16.jpg"
																			data-tooltip="Orange"><span
																				style="background-color:#b75956;"></span></a>
																	</li>
																	<li><a href="shop-banner-right-sidebar.html#" class="mn-opt-clr-img"
																			data-src="assets/img/product/16.jpg"
																			data-src-hover="assets/img/product/15.jpg"
																			data-tooltip="Orange"><span
																				style="background-color:#0e0e0e;"></span></a>
																	</li>
																</ul>
															</div>
															<a href="javascript:void(0);" class="mn-wishlist"
																data-tooltip title="Wishlist">
																<i class="ri-heart-line"></i>
															</a>
														</div>
													</div>
												</div>
											</div>
											<div
												class="col-md-4 col-sm-6 col-xs-6 m-b-24 mn-product-box pro-gl-content">
												<div class="mn-product-card">
													<div class="mn-product-img">
														<div class="mn-img">
															<a href="product-detail.html" class="image">
																<img class="main-img" src="assets/img/product/23.jpg"
																	alt="product">
																<img class="hover-img" src="assets/img/product/24.jpg"
																	alt="product">
															</a>
															<div class="mn-pro-loader"></div>
															<div class="mn-options">
																<ul>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Quick View"
																			data-link-action="quickview"
																			data-bs-toggle="modal"
																			data-bs-target="#quickview_modal"><i
																				class="ri-eye-line"></i></a></li>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Compare" class="mn-compare"><i
																				class="ri-repeat-line"></i></a></li>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Add To Cart" class="mn-add-cart"><i
																				class="ri-shopping-cart-line"></i></a>
																	</li>
																</ul>
															</div>
														</div>
													</div>
													<div class="mn-product-detail">
														<div class="cat">
															<a href="shop-right-sidebar.html">Coats</a>
															<ul>
																<li>s</li>
																<li>m</li>
															</ul>
														</div>
														<h5><a href="shop-banner-right-sidebar.html">Cotton coat for womens</a></h5>
														<p class="mn-info">Lorem Ipsum is simply dummy text of the
															printing and typesetting industry. Lorem Ipsum has been the
															industry's standard dummy text ever since the 1500s.</p>
														<div class="mn-price">
															<div class="mn-price-new">$220</div>
														</div>
														<div class="mn-pro-option">
															<div class="mn-pro-color">
																<ul class="mn-opt-swatch mn-change-img">
																	<li><a href="shop-banner-right-sidebar.html#" class="mn-opt-clr-img"
																			data-src="assets/img/product/23.jpg"
																			data-src-hover="assets/img/product/24.jpg"
																			data-tooltip="Orange"><span
																				style="background-color:#c9a68a;"></span></a>
																	</li>
																	<li><a href="shop-banner-right-sidebar.html#" class="mn-opt-clr-img"
																			data-src="assets/img/product/25.jpg"
																			data-src-hover="assets/img/product/26.jpg"
																			data-tooltip="Orange"><span
																				style="background-color:#d7523e;"></span></a>
																	</li>
																</ul>
															</div>
															<a href="javascript:void(0);" class="mn-wishlist"
																data-tooltip title="Wishlist">
																<i class="ri-heart-line"></i>
															</a>
														</div>
													</div>
												</div>
											</div>
											<div
												class="col-md-4 col-sm-6 col-xs-6 m-b-24 mn-product-box pro-gl-content">
												<div class="mn-product-card">
													<div class="mn-product-img">
														<div class="mn-img">
															<a href="product-detail.html" class="image">
																<img class="main-img" src="assets/img/product/29.jpg"
																	alt="product">
																<img class="hover-img" src="assets/img/product/30.jpg"
																	alt="product">
															</a>
															<div class="mn-pro-loader"></div>
															<div class="mn-options">
																<ul>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Quick View"
																			data-link-action="quickview"
																			data-bs-toggle="modal"
																			data-bs-target="#quickview_modal"><i
																				class="ri-eye-line"></i></a></li>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Compare" class="mn-compare"><i
																				class="ri-repeat-line"></i></a></li>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Add To Cart" class="mn-add-cart"><i
																				class="ri-shopping-cart-line"></i></a>
																	</li>
																</ul>
															</div>
														</div>
													</div>
													<div class="mn-product-detail">
														<div class="cat">
															<a href="shop-right-sidebar.html">T-shirt</a>
															<ul>
																<li>xl</li>
																<li>xxl</li>
															</ul>
														</div>
														<h5><a href="shop-banner-right-sidebar.html">T-shirt for womens</a></h5>
														<p class="mn-info">Lorem Ipsum is simply dummy text of the
															printing and typesetting industry. Lorem Ipsum has been the
															industry's standard dummy text ever since the 1500s.</p>
														<div class="mn-price">
															<div class="mn-price-new">$66</div>
														</div>
														<div class="mn-pro-option">
															<div class="mn-pro-color">
																<ul class="mn-opt-swatch mn-change-img">
																	<li><a href="shop-banner-right-sidebar.html#" class="mn-opt-clr-img"
																			data-src="assets/img/product/29.jpg"
																			data-src-hover="assets/img/product/29.jpg"
																			data-tooltip="Orange"><span
																				style="background-color:#4a63a9;"></span></a>
																	</li>
																	<li><a href="shop-banner-right-sidebar.html#" class="mn-opt-clr-img"
																			data-src="assets/img/product/30.jpg"
																			data-src-hover="assets/img/product/30.jpg"
																			data-tooltip="Orange"><span
																				style="background-color:#303035;"></span></a>
																	</li>
																</ul>
															</div>
															<a href="javascript:void(0);" class="mn-wishlist"
																data-tooltip title="Wishlist">
																<i class="ri-heart-line"></i>
															</a>
														</div>
													</div>
												</div>
											</div>
											<div
												class="col-md-4 col-sm-6 col-xs-6 m-b-24 mn-product-box pro-gl-content">
												<div class="mn-product-card">
													<div class="mn-product-img">
														<div class="mn-img">
															<a href="product-detail.html" class="image">
																<img class="main-img" src="assets/img/product/31.jpg"
																	alt="product">
																<img class="hover-img" src="assets/img/product/32.jpg"
																	alt="product">
															</a>
															<div class="mn-pro-loader"></div>
															<div class="mn-options">
																<ul>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Quick View"
																			data-link-action="quickview"
																			data-bs-toggle="modal"
																			data-bs-target="#quickview_modal"><i
																				class="ri-eye-line"></i></a></li>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Compare" class="mn-compare"><i
																				class="ri-repeat-line"></i></a></li>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Add To Cart" class="mn-add-cart"><i
																				class="ri-shopping-cart-line"></i></a>
																	</li>
																</ul>
															</div>
														</div>
													</div>
													<div class="mn-product-detail">
														<div class="cat">
															<a href="shop-right-sidebar.html">Suit</a>
															<ul>
																<li>s</li>
																<li>m</li>
															</ul>
														</div>
														<h5><a href="shop-banner-right-sidebar.html">Official Men's Suits</a></h5>
														<p class="mn-info">Lorem Ipsum is simply dummy text of the
															printing and typesetting industry. Lorem Ipsum has been the
															industry's standard dummy text ever since the 1500s.</p>
														<div class="mn-price">
															<div class="mn-price-new">$524</div>
														</div>
														<div class="mn-pro-option">
															<div class="mn-pro-color">
																<ul class="mn-opt-swatch mn-change-img">
																	<li><a href="shop-banner-right-sidebar.html#" class="mn-opt-clr-img"
																			data-src="assets/img/product/31.jpg"
																			data-src-hover="assets/img/product/32.jpg"
																			data-tooltip="Orange"><span
																				style="background-color:#485170;"></span></a>
																	</li>
																	<li><a href="shop-banner-right-sidebar.html#" class="mn-opt-clr-img"
																			data-src="assets/img/product/33.jpg"
																			data-src-hover="assets/img/product/34.jpg"
																			data-tooltip="Orange"><span
																				style="background-color:#50814b;"></span></a>
																	</li>
																</ul>
															</div>
															<a href="javascript:void(0);" class="mn-wishlist"
																data-tooltip title="Wishlist">
																<i class="ri-heart-line"></i>
															</a>
														</div>
													</div>
												</div>
											</div>
											<div
												class="col-md-4 col-sm-6 col-xs-6 m-b-24 mn-product-box pro-gl-content">
												<div class="mn-product-card">
													<div class="mn-product-img">
														<div class="mn-img">
															<a href="product-detail.html" class="image">
																<img class="main-img" src="assets/img/product/35.jpg"
																	alt="product">
																<img class="hover-img" src="assets/img/product/36.jpg"
																	alt="product">
															</a>
															<div class="mn-pro-loader"></div>
															<div class="mn-options">
																<ul>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Quick View"
																			data-link-action="quickview"
																			data-bs-toggle="modal"
																			data-bs-target="#quickview_modal"><i
																				class="ri-eye-line"></i></a></li>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Compare" class="mn-compare"><i
																				class="ri-repeat-line"></i></a></li>
																	<li><a href="javascript:void(0)" data-tooltip
																			title="Add To Cart" class="mn-add-cart"><i
																				class="ri-shopping-cart-line"></i></a>
																	</li>
																</ul>
															</div>
														</div>
													</div>
													<div class="mn-product-detail">
														<div class="cat">
															<a href="shop-right-sidebar.html">jacket</a>
															<ul>
																<li>m</li>
																<li>l</li>
															</ul>
														</div>
														<h5><a href="shop-banner-right-sidebar.html">T-shirt with jacket for boy</a></h5>
														<p class="mn-info">Lorem Ipsum is simply dummy text of the
															printing and typesetting industry. Lorem Ipsum has been the
															industry's standard dummy text ever since the 1500s.</p>
														<div class="mn-price">
															<div class="mn-price-new">$866</div>
														</div>
														<div class="mn-pro-option">
															<div class="mn-pro-color">
																<ul class="mn-opt-swatch mn-change-img">
																	<li><a href="shop-banner-right-sidebar.html#" class="mn-opt-clr-img"
																			data-src="assets/img/product/35.jpg"
																			data-src-hover="assets/img/product/36.jpg"
																			data-tooltip="Orange"><span
																				style="background-color:#3b607a;"></span></a>
																	</li>
																	<li><a href="shop-banner-right-sidebar.html#" class="mn-opt-clr-img"
																			data-src="assets/img/product/36.jpg"
																			data-src-hover="assets/img/product/35.jpg"
																			data-tooltip="Orange"><span
																				style="background-color:#19594f;"></span></a>
																	</li>
																</ul>
															</div>
															<a href="javascript:void(0);" class="mn-wishlist"
																data-tooltip title="Wishlist">
																<i class="ri-heart-line"></i>
															</a>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!-- Pagination Start -->
									<div class="mn-pro-pagination m-b-15">
										<span>Showing 1-12 of 21 item(s)</span>
										<ul class="mn-pro-pagination-inner">
											<li><a class="active" href="shop-banner-right-sidebar.html#">1</a></li>
											<li><a href="shop-banner-right-sidebar.html#">2</a></li>
											<li><a href="shop-banner-right-sidebar.html#">3</a></li>
											<li><span>...</span></li>
											<li><a href="shop-banner-right-sidebar.html#">8</a></li>
											<li><a class="next" href="shop-banner-right-sidebar.html#">Next <i
														class="ri-arrow-right-double-line"></i></a>
											</li>
										</ul>
									</div>
									<!-- Pagination End -->
								</div>
								<!--Shop content End -->

							</div>
							<!-- Sidebar Area Start -->
							<div class="mn-shop-sidebar col-lg-3 col-md-12 m-t-991">
								<div id="shop_sidebar">
									<div class="mn-sidebar-wrap">
										<!-- Sidebar Filters Block -->
										<div class="mn-sidebar-block drop">
											<div class="mn-sb-title">
												<h3 class="mn-sidebar-title">Filters</h3>
											</div>
											<div class="mn-sb-block-content p-t-15">
												<ul>
													<li>
														<a href="javascript:void(0)"
															class="mn-sidebar-block-item main drop">clothes</a>
														<ul style="display: block;">
															<li>
																<div class="mn-sidebar-sub-item"><a href="shop-banner-right-sidebar.html#">Men
																		<span>-25</span></a>
																</div>
															</li>
															<li>
																<div class="mn-sidebar-sub-item"><a href="shop-banner-right-sidebar.html#">Women
																		<span>-52</span></a>
																</div>
															</li>
															<li>
																<div class="mn-sidebar-sub-item"><a href="shop-banner-right-sidebar.html#">Boy
																		<span>-40</span></a>
																</div>
															</li>
														</ul>
													</li>
												</ul>
											</div>
											<div class="mn-sb-block-content">
												<ul>
													<li>
														<a href="javascript:void(0)"
															class="mn-sidebar-block-item main drop">cosmetics</a>
														<ul>
															<li>
																<div class="mn-sidebar-sub-item"><a
																		href="shop-right-sidebar.html">Shampoo
																		<span>-25</span></a>
																</div>
															</li>
															<li>
																<div class="mn-sidebar-sub-item"><a
																		href="shop-right-sidebar.html">Body Wash
																		<span>-52</span></a>
																</div>
															</li>
															<li>
																<div class="mn-sidebar-sub-item"><a
																		href="shop-right-sidebar.html">Sunscreen
																		<span>-40</span></a>
																</div>
															</li>
															<li>
																<div class="mn-sidebar-sub-item"><a
																		href="shop-right-sidebar.html">Makeup
																		<span>-35</span></a>
																</div>
															</li>
														</ul>
													</li>
												</ul>
											</div>
											<div class="mn-sb-block-content">
												<ul>
													<li>
														<a href="shop-right-sidebar.html"
															class="mn-sidebar-block-item main">shoes<span>-15</span></a>
													</li>
												</ul>
											</div>
											<div class="mn-sb-block-content">
												<ul>
													<li>
														<a href="shop-right-sidebar.html"
															class="mn-sidebar-block-item main">bag<span>-27</span></a>
													</li>
												</ul>
											</div>
											<div class="mn-sb-block-content">
												<ul>
													<li>
														<a href="javascript:void(0)"
															class="mn-sidebar-block-item main drop">electronics</a>
														<ul>
															<li>
																<div class="mn-sidebar-sub-item"><a
																		href="shop-right-sidebar.html">Laptop
																		<span>-25</span></a>
																</div>
															</li>
															<li>
																<div class="mn-sidebar-sub-item"><a
																		href="shop-right-sidebar.html">Air Conditioner
																		<span>-52</span></a>
																</div>
															</li>
															<li>
																<div class="mn-sidebar-sub-item"><a
																		href="shop-right-sidebar.html">Trimmer
																		<span>-40</span></a>
																</div>
															</li>
															<li>
																<div class="mn-sidebar-sub-item"><a
																		href="shop-right-sidebar.html">Watches
																		<span>-35</span></a>
																</div>
															</li>
														</ul>
													</li>
												</ul>
											</div>
										</div>
										<!-- Sidebar Brand Block -->
										<div class="mn-sidebar-block">
											<div class="mn-sb-title">
												<h3 class="mn-sidebar-title">Brand</h3>
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
										<!-- Sidebar Weight Block -->
										<div class="mn-sidebar-block">
											<div class="mn-sb-title">
												<h3 class="mn-sidebar-title">Size</h3>
											</div>
											<div class="mn-sb-block-content">
												<ul>
													<li>
														<div class="mn-sidebar-block-item">
															<input type="checkbox" value="" checked>
															<a href="shop-banner-right-sidebar.html#">S - Size</a>
															<span class="checked"></span>
														</div>
													</li>
													<li>
														<div class="mn-sidebar-block-item">
															<input type="checkbox" value="">
															<a href="shop-banner-right-sidebar.html#">M - Size</a>
															<span class="checked"></span>
														</div>
													</li>
													<li>
														<div class="mn-sidebar-block-item">
															<input type="checkbox" value="">
															<a href="shop-banner-right-sidebar.html#">L - Size</a>
															<span class="checked"></span>
														</div>
													</li>
													<li>
														<div class="mn-sidebar-block-item">
															<input type="checkbox" value="">
															<a href="shop-banner-right-sidebar.html#">XL - Size</a>
															<span class="checked"></span>
														</div>
													</li>
												</ul>
											</div>
										</div>
										<!-- Sidebar Color item -->
										<div class="mn-sidebar-block color-block mn-sidebar-block-clr">
											<div class="mn-sb-title">
												<h3 class="mn-sidebar-title">Color</h3>
											</div>
											<div class="mn-sb-block-content">
												<ul>
													<li>
														<div class="mn-sidebar-block-item">
															<input type="checkbox" value="">
															<span class="mn-clr-block"
																style="background-color:#c4d6f9;"></span>
															<span class="checked"></span>
														</div>
													</li>
													<li>
														<div class="mn-sidebar-block-item">
															<input type="checkbox" value="">
															<span class="mn-clr-block"
																style="background-color:#ff748b;"></span>
															<span class="checked"></span>
														</div>
													</li>
													<li>
														<div class="mn-sidebar-block-item">
															<input type="checkbox" value="">
															<span class="mn-clr-block"
																style="background-color:#000000;"></span>
															<span class="checked"></span>
														</div>
													</li>
													<li class="active">
														<div class="mn-sidebar-block-item">
															<input type="checkbox" value="">
															<span class="mn-clr-block"
																style="background-color:#2bff4a;"></span>
															<span class="checked"></span>
														</div>
													</li>
													<li>
														<div class="mn-sidebar-block-item">
															<input type="checkbox" value="">
															<span class="mn-clr-block"
																style="background-color:#ff7c5e;"></span>
															<span class="checked"></span>
														</div>
													</li>
													<li>
														<div class="mn-sidebar-block-item">
															<input type="checkbox" value="">
															<span class="mn-clr-block"
																style="background-color:#f155ff;"></span>
															<span class="checked"></span>
														</div>
													</li>
													<li>
														<div class="mn-sidebar-block-item">
															<input type="checkbox" value="">
															<span class="mn-clr-block"
																style="background-color:#ffef00;"></span>
															<span class="checked"></span>
														</div>
													</li>
													<li>
														<div class="mn-sidebar-block-item">
															<input type="checkbox" value="">
															<span class="mn-clr-block"
																style="background-color:#c89fff;"></span>
															<span class="checked"></span>
														</div>
													</li>
													<li>
														<div class="mn-sidebar-block-item">
															<input type="checkbox" value="">
															<span class="mn-clr-block"
																style="background-color:#7bfffa;"></span>
															<span class="checked"></span>
														</div>
													</li>
													<li>
														<div class="mn-sidebar-block-item">
															<input type="checkbox" value="">
															<span class="mn-clr-block"
																style="background-color:#56ffc1;"></span>
															<span class="checked"></span>
														</div>
													</li>
													<li>
														<div class="mn-sidebar-block-item">
															<input type="checkbox" value="">
															<span class="mn-clr-block"
																style="background-color:#ffdb9f;"></span>
															<span class="checked"></span>
														</div>
													</li>
													<li>
														<div class="mn-sidebar-block-item">
															<input type="checkbox" value="">
															<span class="mn-clr-block"
																style="background-color:#9f9f9f;"></span>
															<span class="checked"></span>
														</div>
													</li>
													<li>
														<div class="mn-sidebar-block-item">
															<input type="checkbox" value="">
															<span class="mn-clr-block"
																style="background-color:#6556ff;"></span>
															<span class="checked"></span>
														</div>
													</li>
												</ul>
											</div>
										</div>
										<!-- Sidebar Price Block -->
										<div class="mn-sidebar-block">
											<div class="mn-sb-title">
												<h3 class="mn-sidebar-title">Price</h3>
											</div>
											<div class="mn-sb-block-content mn-price-range-slider es-price-slider">
												<div class="mn-price-filter">
													<div class="mn-price-input">
														<label class="filter__label">
															From<input type="text" class="filter__input">
														</label>
														<span class="mn-price-divider"></span>
														<label class="filter__label">
															To<input type="text" class="filter__input">
														</label>
													</div>
													<div id="mn-sliderPrice" class="filter__slider-price" data-min="0"
														data-max="250" data-step="10"></div>
												</div>
											</div>
										</div>
										<!-- Sidebar tags -->
										<div class="mn-sidebar-block">
											<div class="mn-sb-title">
												<h3 class="mn-sidebar-title">Tags</h3>
											</div>
											<div class="mn-tag-block mn-sb-block-content">
												<a href="shop-right-sidebar.html"><span>Clothes</span></a>
												<a href="shop-right-sidebar.html"><span>Fruits</span></a>
												<a href="shop-right-sidebar.html"><span>Snacks</span></a>
												<a href="shop-right-sidebar.html"><span>Dairy</span></a>
												<a href="shop-right-sidebar.html"><span>Seafood</span></a>
												<a href="shop-right-sidebar.html"><span>Fastfood</span></a>
												<a href="shop-right-sidebar.html"><span>Toys</span></a>
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