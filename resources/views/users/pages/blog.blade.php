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
						<h2 class="mn-breadcrumb-title">Bài viết</h2>
					</div>
					<div class="col-md-6 col-sm-12">
						<!-- mn-breadcrumb-list start -->
						<ul class="mn-breadcrumb-list">
							<li class="mn-breadcrumb-item"><a href="index.html">Trang chủ</a></li>
							<li class="mn-breadcrumb-item active">Bài viết</li>
						</ul>
						<!-- mn-breadcrumb-list end -->
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row m-b-30">
		<div class="mn-blogs-rightside col-lg-8 col-md-12">
			<!-- Blog content Start -->
			<div class="mn-blogs-content mn-blog">
				<div class="mn-blogs-inner">
					<div class="row">
						@foreach($blogs as $blog)
						<div class="col-sm-6 col-12 mn-blog-block m-b-24">
							<div class="mn-blog-card">
								<div class="blog-info">
									<figure class="blog-img">
										<a href="{{ route('blogdetail', ['id' => $blog->id]) }}">
											<img src="{{ asset($blog->image ?? 'assets/img/blog/1.jpg') }}" alt="">
										</a>
									</figure>

									<div class="detail">
										<label>
											{{ \Carbon\Carbon::parse($blog->create_date)->format('d/m/Y') }}
											-
											<a href="#">
												{{ $blog->news_categories->name ?? '' }}
											</a>
										</label>

										<h3>
											<a href="{{ route('blogdetail', ['id' => $blog->id]) }}">
												{{ $blog->title }}
											</a>
										</h3>

										<div class="more-info">
											<a href="{{ route('blogdetail', ['id' => $blog->id]) }}">
												Read More <i class="ri-arrow-right-double-line"></i>
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						@endforeach
					</div>

				</div>
				<!-- Pagination Start -->
				<div class="mn-pro-pagination mt-4">
					{{ $blogs->links() }}
				</div>
				<!-- Pagination End -->
			</div>
			<!--Blog content End -->
		</div>

		<!-- Sidebar Area Start -->
		<div class="mn-blogs-sidebar mn-blogs-leftside col-lg-4 col-md-12 m-t-991">
			<div class="mn-blog-search">
				<form class="mn-blog-search-form" action="blog-right-sidebar.html#">
					<input class="form-control" placeholder="Search Our Blog" type="text">
					<button class="submit" type="submit"><i class="ri-search-line"></i></button>
				</form>
			</div>
			<div class="mn-blog-sidebar-wrap">
				<!-- Sản phẩm mới -->
				<div class="mn-sidebar-block mn-sidebar-recent-blog">
					<div class="mn-sb-title">
						<h3 class="mn-sidebar-title">Sản phẩm mới</h3>
					</div>

					<div class="mn-blog-block-content mn-sidebar-dropdown">
						@foreach ($productnew as $product)
						<div class="mn-sidebar-block-item">
							<div class="mn-sidebar-block-img">
								<img src="{{ asset($product->images[0]->src ?? 'assets/img/product/default.jpg') }}"
									alt="{{ $product->name }}">
							</div>

							<div class="mn-sidebar-block-detial">
								<h5 class="mn-blog-title">
									<a href="{{ route('product', ['product_id' => $product->id]) }}">
										{{ $product->name }}
									</a>
								</h5>
								<div class="mn-blog-date">
									{{ $product->create_date }}
								</div>
							</div>
						</div>
						@endforeach
					</div>
				</div>
				<form method="GET" id="blogFilterForm">
					<!-- Thể loại bài viết -->
					<div class="mn-sidebar-block mn-sidebar-recent-blog">
						<div class="mn-sb-title">
							<h3 class="mn-sidebar-title">Thể loại bài viết</h3>
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
											<span>{{ $cat->name }}</span>
										</label>
										<span class="checked"></span>
									</div>
								</li>
								@endforeach
							</ul>
						</div>
					</div>
					<div class="mt-3">
						<button type="submit" class="btn btn-primary btn-sm">Áp dụng</button>
						<a href="{{ route('blog') }}" class="btn btn-link btn-sm">Xóa bộ lọc</a>
					</div>
				</form>

				
			</div>


		</div>
	</div>
	@push('scripts')
	<script>
		document.querySelectorAll('.filter-checkbox').forEach(cb => {
			cb.addEventListener('change', () => {
				document.getElementById('blogFilterForm').submit();
			});
		});
	</script>
	@endpush
	@endsection