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
								<h2 class="mn-breadcrumb-title">Login Page</h2>
							</div>
							<div class="col-md-6 col-sm-12">
								<!-- mn-breadcrumb-list start -->
								<ul class="mn-breadcrumb-list">
									<li class="mn-breadcrumb-item"><a href="index.html">Home</a></li>
									<li class="mn-breadcrumb-item active">Login Page</li>
								</ul>
								<!-- mn-breadcrumb-list end -->
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Login section -->
			<section class="mn-login p-b-15">
				<div class="mn-title d-none">
					<h2>Login<span></span></h2>
					<p>Get access to your Orders, Wishlist and Recommendations.</p>
				</div>
				<div class="mn-login-content">
					<div class="mn-login-box">
						<div class="mn-login-wrapper">
							<div class="mn-login-container">
								<div class="mn-login-form">
									<form action="login.html#" method="post">
										<span class="mn-login-wrap">
											<label>Email Address*</label>
											<input type="text" name="name" placeholder="Enter your email add..."
												required>
										</span>
										<span class="mn-login-wrap">
											<label>Password*</label>
											<input type="password" name="password" placeholder="Enter your password"
												required>
										</span>
										<span class="mn-login-wrap mn-login-fp">
											<span class="mn-remember">
												<input type="checkbox" value="">
												Remember
												<span class="checked"></span>
											</span>
											<label><a href="login.html#">Forgot Password?</a></label>
										</span>
										<span class="mn-login-wrap mn-login-btn">
											<span><a href="register.html" class="">Create Account?</a></span>
											<button class="mn-btn-1 btn" type="submit"><span>Login</span></button>
										</span>
									</form>
								</div>
							</div>
						</div>
					</div>
					<div class="mn-login-box d-n-991">
						<div class="mn-login-img">
							<img src="assets/img/common/about-3.png" alt="login">
						</div>
					</div>
				</div>
			</section>
		</div>
@endsection