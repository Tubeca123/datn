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
							<li class="mn-breadcrumb-item active">Chi tiết bài viết</li>
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
			<div class="mn-blogs-content">
				<div class="mn-blogs-inner">
					<div class="mn-single-blog-item">
						<div class="single-blog-info">
							<figure class="blog-img">
								<img src="{{ asset($blog->image) }}" alt="{{ $blog->title }}">
							</figure>
							<div class="single-blog-detail">
								<label>
									{{ $blog->create_date }}
									-
									<a href="#">
										{{ $blog->category->name ?? 'Uncategorized' }}
									</a>
								</label>
								<h3>{{$blog->title}}</h3>
								<p class="mn-text">{{$blog->content}}</p>
								
							</div>
						</div>
					</div>
				</div>
				
				<!-- Comments End -->
			</div>
		</div>

		<!-- Sidebar Area Start -->
		<div class="mn-blogs-sidebar mn-blogs-leftside col-lg-4 col-md-12 m-t-991">
			<div class="mn-blog-search">
				<form class="mn-blog-search-form" action="blog-detail-right-sidebar.html#">
					<input class="form-control" placeholder="Search Our Blog" type="text">
					<button class="submit" type="submit"><i class="ri-search-line"></i></button>
				</form>
			</div>
			<div class="mn-blog-sidebar-wrap">
				<!-- Sidebar Recent Blog Block -->
				<div class="mn-sidebar-block mn-sidebar-recent-blog">
					<div class="mn-sb-title">
						<h3 class="mn-sidebar-title">Sản phẩm bán chạy</h3>
					</div>
					<div class="mn-blog-block-content mn-sidebar-dropdown">
						@foreach ($producthot as $product)
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
				<!-- Sidebar Recent Blog Block -->
				<!-- Sidebar Category Block -->
				<div class="mn-sidebar-block">
					
				</div>
				<!-- Sidebar Category Block -->
			</div>
		</div>
	</div>
</div>
@endsection