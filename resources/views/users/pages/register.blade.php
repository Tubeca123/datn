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
								<h2 class="mn-breadcrumb-title">Trang đăng ký</h2>
							</div>
							<div class="col-md-6 col-sm-12">
								<!-- mn-breadcrumb-list start -->
								<ul class="mn-breadcrumb-list">
									<li class="mn-breadcrumb-item"><a href="index.html">Trang chủ</a></li>
									<li class="mn-breadcrumb-item active">Đăng ký</li>
								</ul>
								<!-- mn-breadcrumb-list end -->
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Register section -->
			<section class="mn-register p-b-15">
				
				<div class="row">
					<div class="mn-register-wrapper">
						<div class="mn-register-container">
							<div class="mn-register-form">
								<form action="{{route('handle_user_register')}}" method="post">
									@csrf
									<span class="mn-register-wrap mn-register-half">
										<label>Tên đăng nhập*</label>
										<input type="text" name="name"  value="{{ old('name')}}" placeholder="vd: Nguyễn Hữu Tú"
											required>
									</span>
									<span class="mn-register-wrap mn-register-half">
										<label>Số điện thoại*</label>
										<input type="text" name="phone"  value="{{ old('phone')}}" placeholder="vd: 0123456789"
											required>
									</span>
									<span class="mn-register-wrap">
										<label>Email*</label>
										@if($errors->has('email'))
											<text class="text-danger">
												{{ $errors->first('email') }}
											</text>
										@endif
										<input type="email" name="email" value="{{ old('email')}}" placeholder="nhập gmail ở đây" required>
									</span>
									<span class="mn-register-wrap">
										<label>Địa chỉ</label>
										<input type="text" name="address"  value="{{ old('address')}}" placeholder="nhập địa chỉ ở đây" required>
									</span>
										@if($errors->has('password'))
											<text class="text-danger">
												{{ $errors->first('password') }}
											</text>
										@endif
									<span class="mn-register-wrap mn-register-half">
										<label>Mật khẩu</label>
										<input type="password" name="password" required>
									</span>
									<span class="mn-register-wrap mn-register-half">
										<label>Nhập lại mật khẩu</label>
										<input type="password" name="password_confirmation" required>
									</span>
									<span class="mn-register-wrap mn-register-btn">
										<span>Bạn đã có tài khoản?<a href="login.html">Đăng nhập</a></span>
										<button class="mn-btn-1" type="submit"><span>Đăng ký</span></button>
									</span>
								</form>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
@endsection