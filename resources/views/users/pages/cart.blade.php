@extends('users/master_layout')

@section('title')
<title>Giỏ hàng</title>
@endsection

@section('content')

<div class="mn-main-content">
	<!-- ...Breadcrumb giữ nguyên... -->

	<section class="mn-cart-section p-b-15">
		<div class="row">
			<div class="mn-cart-leftside col-lg-8 col-md-12">
				<div class="mn-cart-content">
					<div class="mn-cart-inner cart_list">
						<div class="row">
							 
								<div class="table-content cart-table-content">
									<table class="table">
										<thead>
											<tr>
												<th>Sản phẩm</th>
												<th>Giá tiền</th>
												<th>Đơn vị</th>
												<th style="text-align: center;">Số lượng</th>
												<th>Tổng tiền</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											@forelse($cartItems as $item)
											<tr class="mn-cart-product">
												<td data-label="Product" class="mn-cart-pro-name">
													<a href="{{ route('product',['product_id'=> $item->product_id])}}) }}">
														<img class="mn-cart-pro-img"
															src="{{asset($item->product->images[0]->src ?? '')}}"
															alt="" style="width:60px;height:60px;object-fit:cover;margin-right:10px;">
														{{ optional($item->product)->name ?? 'Sản phẩm' }}
													</a>
												</td>

												<td data-label="Price" class="mn-cart-pro-price">
													<span class="amount">{{ number_format($item->price, 0, ',', '.') }} đ</span>
												</td>

												<td data-label="Unit" class="mn-cart-pro-unit">
													{{ $item->productUnit->unit->name}}
												</td>

												<td data-label="Quantity" class="mn-cart-pro-qty" style="text-align: center;">
													<form action="{{ route('cart.update') }}" method="POST" class="d-inline-flex align-items-center">
														@csrf
														<input type="hidden" name="cart_id" value="{{ $item->id }}">
														<input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control" style="width:80px; margin-right:5px;">
														<button type="submit" class="btn btn-sm btn-primary">Cập nhật</button>
													</form>
												</td>


												<td data-label="Total" class="mn-cart-pro-subtotal">
													{{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ
												</td>

												<td data-label="Remove" class="mn-cart-pro-remove">
													<form action="{{ route('cart.remove') }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn xóa sản phẩm này khỏi giỏ?')">
														@csrf
														<input type="hidden" name="cart_id" value="{{ $item->id }}">
														<button type="submit" class="btn btn-link text-danger"><i class="ri-delete-bin-line"></i> Xóa</button>
													</form>
												</td>
											</tr>
											@empty
											<tr>
												<td colspan="6" class="text-center text-muted">
													<i class="fas fa-inbox fa-3x mb-3"></i>
													<p>Giỏ hàng trống</p>
												</td>
											</tr>
											@endforelse
										</tbody>
									</table>
								</div>

								<div class="col-lg-12">
									<div class="mn-cart-update-bottom">
										<a href="cart.html#">Trang chủ</a>
										<a href="{{ route('checkout') }}"><button class="mn-btn-2"><span>Mua hàng</span></button></a>
									</div>
								</div>
							 
						</div>
					</div>
				</div>
			</div>

			<!-- Sidebar -->
			<div class="mn-cart-rightside col-lg-4 col-md-12 m-t-991">
				<div class="mn-sidebar-wrap">
					<div class="mn-sidebar-block">
						<div class="mn-sb-title">
							<h3 class="mn-sidebar-title">Thông tin đơn hàng</h3>
						</div>
						<div class="mn-sb-block-content">
							<div class="mn-cart-summary">
								<div class="d-flex justify-content-between">
									<span>Subtotal</span>
									<span>{{ number_format($subtotal, 0, ',', '.') }} đ</span>
								</div>
								<div class="d-flex justify-content-between">
									<span>Phí vận chuyển</span>
									<span>{{ number_format($delivery, 0, ',', '.') }} đ</span>
								</div>

								<hr>

								<div class="mn-cart-summary-total d-flex justify-content-between">
									<strong>Total</strong>
									<strong>{{ number_format($total, 0, ',', '.') }} đ</strong>
								</div>

								<div class="mt-3">
									<a href="{{ route('checkout') }}" class="btn btn-primary btn-block">Tiến hành thanh toán</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</section>
</div>


@endsection