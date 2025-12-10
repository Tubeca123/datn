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
								<h2 class="mn-breadcrumb-title">Contact us Page</h2>
							</div>
							<div class="col-md-6 col-sm-12">
								<!-- mn-breadcrumb-list start -->
								<ul class="mn-breadcrumb-list">
									<li class="mn-breadcrumb-item"><a href="index.html">Home</a></li>
									<li class="mn-breadcrumb-item active">Contact us Page</li>
								</ul>
								<!-- mn-breadcrumb-list end -->
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Contact us section -->
			<section class="mn-contact p-b-15">
				<div class="mn-title d-none">
					<h2>Get in <span>Touch</span></h2>
					<p>Please select a topic below related to you inquiry. If you don't fint what you need, fill out
						our
						contact form.</p>
				</div>
				<div class="row p-t-80">
					<div class="col-md-6 mn-contact-detail m-b-m-30">
						<div class="mn-box m-b-30">
							<div class="detail">
								<div class="icon"><i class="ri-mail-send-line"></i></div>
								<div class="info">
									<h3 class="title">Contact Mail</h3>
									<p>mail.example@gmail.com</p>
								</div>
							</div>
						</div>
						<div class="mn-box m-b-30">
							<div class="detail">
								<div class="icon"><i class="ri-customer-service-2-line"></i></div>
								<div class="info">
									<h3 class="title">Contact Phone</h3>
									<p>(+91)-9876XXXXX</p>
								</div>
							</div>
						</div>
						<div class="mn-box m-b-30">
							<div class="detail">
								<div class="icon"><i class="ri-map-pin-line"></i></div>
								<div class="info">
									<h3 class="title">Address</h3>
									<p>Ruami Moraes Filho, Salvador - MA, 40352, Brazil.</p>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6 m-t-767">
						<form>
							<div class="form-group">
								<input type="text" class="form-control" id="fname" placeholder="Full Name">
							</div>
							<div class="form-group">
								<input type="email" class="form-control" id="umail" placeholder="Email">
							</div>
							<div class="form-group">
								<input type="text" class="form-control" id="phone" placeholder="Phone">
							</div>
							<div class="form-group">
								<textarea class="form-control" id="message" rows="5"
									placeholder="Message"></textarea>
							</div>
							<button type="submit" class="mn-btn-2"><span>Submit</span></button>
						</form>
					</div>
				</div>
			</section>
		</div>
@endsection