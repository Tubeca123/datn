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
								<h2 class="mn-breadcrumb-title">Compare Page</h2>
							</div>
							<div class="col-md-6 col-sm-12">
								<!-- mn-breadcrumb-list start -->
								<ul class="mn-breadcrumb-list">
									<li class="mn-breadcrumb-item"><a href="index.html">Home</a></li>
									<li class="mn-breadcrumb-item active">Compare Page</li>
								</ul>
								<!-- mn-breadcrumb-list end -->
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Compare section -->
			<section class="mn-compare-list p-b-15">
				<h2 class="d-none">Compare</h2>
				<div class="row">
					<div class="col-md-12">
						<div class="mn-compare-box">
							<div class="mn-compare-col title-col">
								<div class="mn-compare-cell">
									<div class="title">
										<h5>Product Image</h5>
									</div>
								</div>
								<div class="mn-compare-cell">
									<h5>Name</h5>
								</div>
								<div class="mn-compare-cell">
									<h5>Category</h5>
								</div>
								<div class="mn-compare-cell">
									<h5>Ratings</h5>
								</div>
								<div class="mn-compare-cell">
									<h5>Availability</h5>
								</div>
								<div class="mn-compare-cell">
									<h5>location</h5>
								</div>
								<div class="mn-compare-cell">
									<h5>Brand</h5>
								</div>
								<div class="mn-compare-cell">
									<h5>SKU</h5>
								</div>
								<div class="mn-compare-cell">
									<h5>Quantity</h5>
								</div>
								<div class="mn-compare-cell">
									<h5>Size</h5>
								</div>
								<div class="mn-compare-cell">
									<div class="desc">
										<h5>Description</h5>
									</div>
								</div>
							</div>
							<div class="mn-compare-col product-col">
								<a href="javascript:void(0)" class="remove-compare-product"><i
										class="ri-close-large-line"></i></a>
								<div class="mn-compare-cell">
									<div class="list">
										<img src="assets/img/product/31.jpg" alt="product">
										<div class="mn-action">
											<ul>
												<li>
													<a class="mn-btn-group wishlist mn-wishlist" title="Wishlist"><i
															class="ri-heart-line"></i></a>
												</li>
												<li>
													<a href="javascript:void(0)" title="Add To Cart"
														class="mn-btn-group add-to-cart mn-add-cart"><i
															class="ri-shopping-cart-line"></i></a>
												</li>
											</ul>
										</div>
									</div>
								</div>
								<div class="mn-compare-cell">
									<p>Men office suit cotton</p>
								</div>
								<div class="mn-compare-cell">
									<p>Snack & Spices</p>
								</div>
								<div class="mn-compare-cell">
									<span class="mn-pro-rating">
										<i class="ri-star-fill"></i>
										<i class="ri-star-fill"></i>
										<i class="ri-star-fill"></i>
										<i class="ri-star-fill"></i>
										<i class="ri-star-fill grey"></i>
									</span>
									<p class="rating-info">(15 Review)</p>
								</div>
								<div class="mn-compare-cell">
									<p class="i-stock">In Stock</p>
								</div>
								<div class="mn-compare-cell">
									<p>In Store , Online</p>
								</div>
								<div class="mn-compare-cell">
									<p>Bhisma Fashion</p>
								</div>
								<div class="mn-compare-cell">
									<p>54786</p>
								</div>
								<div class="mn-compare-cell">
									<p>1</p>
								</div>
								<div class="mn-compare-cell">
									<p>XL</p>
								</div>
								<div class="mn-compare-cell">
									<div class="desc">
										<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour.</p>
									</div>
								</div>
							</div>
							<div class="mn-compare-col product-col">
								<a href="javascript:void(0)" class="remove-compare-product"><i
										class="ri-close-large-line"></i></a>
								<div class="mn-compare-cell">
									<div class="list">
										<img src="assets/img/product/1.jpg" alt="product">
										<div class="mn-action">
											<ul>
												<li>
													<a class="mn-btn-group wishlist mn-wishlist" title="Wishlist"><i
															class="ri-heart-line"></i></a>
												</li>
												<li>
													<a href="javascript:void(0)" title="Add To Cart"
														class="mn-btn-group add-to-cart mn-add-cart"><i
															class="ri-shopping-cart-line"></i></a>
												</li>
											</ul>
										</div>
									</div>
								</div>
								<div class="mn-compare-cell">
									<p>Round neck cotton t-shirt</p>
								</div>
								<div class="mn-compare-cell">
									<p>T-shirt</p>
								</div>
								<div class="mn-compare-cell">
									<span class="mn-pro-rating">
										<i class="ri-star-fill"></i>
										<i class="ri-star-fill"></i>
										<i class="ri-star-fill"></i>
										<i class="ri-star-fill"></i>
										<i class="ri-star-fill"></i>
									</span>
									<p class="rating-info">(654 Review)</p>
								</div>
								<div class="mn-compare-cell">
									<p class="o-stock">Out Of Stock</p>
								</div>
								<div class="mn-compare-cell">
									<p>Online</p>
								</div>
								<div class="mn-compare-cell">
									<p>Darsh Store</p>
								</div>
								<div class="mn-compare-cell">
									<p>85725</p>
								</div>
								<div class="mn-compare-cell">
									<p>2</p>
								</div>
								<div class="mn-compare-cell">
									<p>L</p>
								</div>
								<div class="mn-compare-cell">
									<div class="desc">
										<p>Recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
									</div>
								</div>
							</div>
							<div class="mn-compare-col product-col">
								<a href="javascript:void(0)" class="remove-compare-product"><i
										class="ri-close-large-line"></i></a>
								<div class="mn-compare-cell">
									<div class="list">
										<img src="assets/img/product/17.jpg" alt="product">
										<div class="mn-action">
											<ul>
												<li>
													<a class="mn-btn-group wishlist mn-wishlist" title="Wishlist"><i
															class="ri-heart-line"></i></a>
												</li>
												<li>
													<a href="javascript:void(0)" title="Add To Cart"
														class="mn-btn-group add-to-cart mn-add-cart"><i
															class="ri-shopping-cart-line"></i></a>
												</li>
											</ul>
										</div>
									</div>
								</div>
								<div class="mn-compare-cell">
									<p>T-shirt for womens</p>
								</div>
								<div class="mn-compare-cell">
									<p>Clothes</p>
								</div>
								<div class="mn-compare-cell">
									<span class="mn-pro-rating">
										<i class="ri-star-fill"></i>
										<i class="ri-star-fill"></i>
										<i class="ri-star-fill"></i>
										<i class="ri-star-fill grey"></i>
										<i class="ri-star-fill grey"></i>
									</span>
									<p class="rating-info">(264 Review)</p>
								</div>
								<div class="mn-compare-cell">
									<p class="i-stock">In Stock</p>
								</div>
								<div class="mn-compare-cell">
									<p>In Store</p>
								</div>
								<div class="mn-compare-cell">
									<p>Peoples Store</p>
								</div>
								<div class="mn-compare-cell">
									<p>2546</p>
								</div>
								<div class="mn-compare-cell">
									<p>1</p>
								</div>
								<div class="mn-compare-cell">
									<p>M</p>
								</div>
								<div class="mn-compare-cell">
									<div class="desc">
										<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard.</p>
									</div>
								</div>
							</div>
							<div class="mn-compare-col product-col">
								<a href="javascript:void(0)" class="remove-compare-product"><i
										class="ri-close-large-line"></i></a>
								<div class="mn-compare-cell">
									<div class="list">
										<img src="assets/img/product/35.jpg" alt="product">
										<div class="mn-action">
											<ul>
												<li>
													<a class="mn-btn-group wishlist mn-wishlist" title="Wishlist"><i
															class="ri-heart-line"></i></a>
												</li>
												<li>
													<a href="javascript:void(0)" title="Add To Cart"
														class="mn-btn-group add-to-cart mn-add-cart"><i
															class="ri-shopping-cart-line"></i></a>
												</li>
											</ul>
										</div>
									</div>
								</div>
								<div class="mn-compare-cell">
									<p>Tshirt with jacket</p>
								</div>
								<div class="mn-compare-cell">
									<p>Fashion</p>
								</div>
								<div class="mn-compare-cell">
									<span class="mn-pro-rating">
										<i class="ri-star-fill"></i>
										<i class="ri-star-fill"></i>
										<i class="ri-star-fill"></i>
										<i class="ri-star-fill grey"></i>
										<i class="ri-star-fill grey"></i>
									</span>
									<p class="rating-info">(325 Review)</p>
								</div>
								<div class="mn-compare-cell">
									<p class="i-stock">In Stock</p>
								</div>
								<div class="mn-compare-cell">
									<p>In Store</p>
								</div>
								<div class="mn-compare-cell">
									<p>Mariyas Store</p>
								</div>
								<div class="mn-compare-cell">
									<p>6542</p>
								</div>
								<div class="mn-compare-cell">
									<p>2</p>
								</div>
								<div class="mn-compare-cell">
									<p>XL</p>
								</div>
								<div class="mn-compare-cell">
									<div class="desc">
										<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's.</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
@endsection