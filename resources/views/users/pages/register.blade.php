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
								<h2 class="mn-breadcrumb-title">Register Page</h2>
							</div>
							<div class="col-md-6 col-sm-12">
								<!-- mn-breadcrumb-list start -->
								<ul class="mn-breadcrumb-list">
									<li class="mn-breadcrumb-item"><a href="index.html">Home</a></li>
									<li class="mn-breadcrumb-item active">Register Page</li>
								</ul>
								<!-- mn-breadcrumb-list end -->
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Register section -->
			<section class="mn-register p-b-15">
				<div class="mn-title d-none">
					<h2>Register<span></span></h2>
					<p>Best place to buy and sell digital products.</p>
				</div>
				<div class="row">
					<div class="mn-register-wrapper">
						<div class="mn-register-container">
							<div class="mn-register-form">
								<form action="register.html#" method="post">
									<span class="mn-register-wrap mn-register-half">
										<label>First Name*</label>
										<input type="text" name="firstname" placeholder="Enter your first name"
											required>
									</span>
									<span class="mn-register-wrap mn-register-half">
										<label>Last Name*</label>
										<input type="text" name="lastname" placeholder="Enter your last name" required>
									</span>
									<span class="mn-register-wrap mn-register-half">
										<label>Email*</label>
										<input type="email" name="email" placeholder="Enter your email add..." required>
									</span>
									<span class="mn-register-wrap mn-register-half">
										<label>Phone Number*</label>
										<input type="text" name="phonenumber" placeholder="Enter your phone number"
											required>
									</span>
									<span class="mn-register-wrap">
										<label>Address</label>
										<input type="text" name="address" placeholder="Address Line 1">
									</span>
									<span class="mn-register-wrap mn-register-half">
										<label>City *</label>
										<span class="mn-rg-select-inner">
											<select name="gi_select_city" id="mn-select-city"
												class="mn-register-select">
												<option selected disabled>City</option>
												<option value="1">City 1</option>
												<option value="2">City 2</option>
												<option value="3">City 3</option>
												<option value="4">City 4</option>
												<option value="5">City 5</option>
											</select>
										</span>
									</span>
									<span class="mn-register-wrap mn-register-half">
										<label>Post Code</label>
										<input type="text" name="postalcode" placeholder="Post Code">
									</span>
									<span class="mn-register-wrap mn-register-half">
										<label>Country *</label>
										<span class="mn-rg-select-inner">
											<select name="gi_select_country" id="mn-select-country"
												class="mn-register-select">
												<option selected disabled>Country</option>
												<option value="1">Country 1</option>
												<option value="2">Country 2</option>
												<option value="3">Country 3</option>
												<option value="4">Country 4</option>
												<option value="5">Country 5</option>
											</select>
										</span>
									</span>
									<span class="mn-register-wrap mn-register-half">
										<label>Region State</label>
										<span class="mn-rg-select-inner">
											<select name="gi_select_state" id="mn-select-state"
												class="mn-register-select">
												<option selected disabled>Region/State</option>
												<option value="1">Region/State 1</option>
												<option value="2">Region/State 2</option>
												<option value="3">Region/State 3</option>
												<option value="4">Region/State 4</option>
												<option value="5">Region/State 5</option>
											</select>
										</span>
									</span>
									<span class="mn-register-wrap mn-recaptcha">
										<span class="g-recaptcha"
											data-sitekey="6LfKURIUAAAAAO50vlwWZkyK_G2ywqE52NU7YO0S"
											data-callback="verifyRecaptchaCallback"
											data-expired-callback="expiredRecaptchaCallback"></span>
										<input class="form-control d-none" data-recaptcha="true" required
											data-error="Please complete the Captcha">
										<span class="help-block with-errors"></span>
									</span>
									<span class="mn-register-wrap mn-register-btn">
										<span>Have an account?<a href="login.html">Login</a></span>
										<button class="mn-btn-1" type="submit"><span>Register</span></button>
									</span>
								</form>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
@endsection