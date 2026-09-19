@extends('frontend.layoutIndex')

@section('bodyIndex')

<!--==================================================-->
<!-- Start Breadcumb Area -->
<!--==================================================-->
<div class="breadcumb-area d-flex">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-12 text-center">
				<div class="breadcumb-content">
					<div class="breadcumb-title">
						<h4>About Us</h4>
					</div>
					<ul>
						<li>
							<a href="{{ route('_homeIndex') }}">
								<i class="bi bi-house-door-fill"></i> Home
							</a>
						</li>
						<li class="rotates">
							<i class="bi bi-slash-lg"></i> About Us
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<!--==================================================-->
<!-- End Breadcumb Area -->
<!--==================================================-->


<!--==================================================-->
<!-- Start About Area Style Two-->
<!--==================================================-->
<section class="about_area style_two">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-6 col-md-12">
				<div class="about_thumb">
					<img src="assets/images/home_two/about_2.png" alt="">
					<div class="about_play style_two">
						<a data-aos="flip-left" class="banner-play-btn">
							<div class="text-inner">
								<svg xmlns="http://www.w3.org/2000/svg" width="250.5" height="250.5" viewBox="0 0 250.5 250.5">
									<path d="M.25,125.25a125,125,0,1,1,125,125,125,125,0,0,1-125-125" id="e-path-35ee1b2"></path>
									<text>
										<textPath href="#e-path-35ee1b2" startOffset="0%">
											Registered in India  *  RBI COMPLIANT  *
										</textPath>
									</text>
								</svg>
							</div>
							<div class="like">
								<img src="assets/images/home_two/like.png" alt="">
							</div>
						</a>
					</div>
				</div>
			</div>

			<div class="col-lg-6 col-md-12">
				<div class="section_title">
					<h4>FINTECH CONSULTING</h4>
					<h1>Trusted Digital Loan</h1>
					<h1>Consultancy Platform</h1>
					<p>
						{{ config('web.store_data.app_name') }} is a registered Limited Liability Partnership in India. We operate as a digital loan consultancy platform connecting users with trusted financial institutions including banks and NBFCs.
					</p>
                    <p>
                        Our goal is to simplify the loan process by providing users with quick access to verified lending partners.
                    </p>
                    <p>
                        We do not provide loans directly and act solely as a technology and service platform.
                    </p>
				</div>

				<div class="row">
					<div class="col-lg-6 col-md-6">
						<div class="about-icon_box">
							<div class="about_icon">
								<img src="assets/images/home_two/about_icon1.png" alt="">
							</div>
							<div class="about_content style_two">
								<h3>Loan Eligibility & Advisory</h3>
							</div>
						</div>
					</div>

					<div class="col-lg-6 col-md-6">
						<div class="about-icon_box">
							<div class="about_icon">
								<img src="assets/images/home_two/about_icon2.png" alt="">
							</div>
							<div class="about_content style_two">
								<h3>Secure & Guided Applications</h3>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
	<div class="about_shape two">
		<img src="assets/images/home_two/about_shape2.png" alt="">
	</div>
</section>
<!--==================================================-->
<!-- End About Area Style Two-->
<!--==================================================-->


<!--==================================================-->
<!-- Start Counter Area-->
<!--==================================================-->
<section class="counter_area boxed">
	<div class="container">
		<div class="counter_upper">
			<div class="row align-items-center">
				<div class="col-lg-8">
					<div class="section_title style_two">
						<h1>Transparent, Secure &</h1>
						<h1>RBI-Compliant Loan Guidance</h1>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="consalt_btn text-right">
						<a href="#">Get Started Now <span></span></a>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4 col-md-6">
				<div class="counter-single-item">
					<div class="counter-content none">
						<div class="counter-_number">
							<h1 class="counter">100</h1>
							<span>%</span>
						</div>
						<div class="counter_title">
							<h5>RBI Guideline <br>Compliance</h5>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-4 col-md-6">
				<div class="counter-single-item">
					<div class="counter-content">
						<div class="counter-_number">
							<h1 class="counter">0</h1>
							<span></span>
						</div>
						<div class="counter_title">
							<h5>Hidden Fees & <br>Unauthorized Access</h5>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-4 col-md-6">
				<div class="counter-single-item">
					<div class="counter-content">
						<div class="counter-_number">
							<h1 class="counter">24</h1>
							<span>/7</span>
						</div>
						<div class="counter_title">
							<h5>Customer Support <br>Assistance</h5>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!--==================================================-->
<!-- End Counter Area-->
<!--==================================================-->


<!--==================================================-->
<!-- Start Why Choose Us Area -->
<!--==================================================-->
<section class="why_choose_us">
	<div class="container">
		<div class="row">
			<div class="col-lg-6 col-md-12">
				<div class="choose_thumb">
					<img src="assets/images/home_3/choose_thumb.png" alt="">
				</div>
			</div>

			<div class="col-lg-6 col-md-12">
				<div class="choose_right">
					<div class="section_title style_three pb-13">
						<h4>WHY CHOOSE US</h4>
						<h1>Responsible & Ethical</h1>
						<h1>FinTech Solutions</h1>
						<p>
							We operate strictly as a technology and service provider — not a lender.
							Our platform ensures complete transparency, data privacy, and ethical
							fintech practices while guiding users toward the right financial partners.
						</p>
					</div>

					<div class="row">
						<div class="col-lg-6 col-md-6">
							<div class="choose_list">
								<ul>
									<li><i class="bi bi-check-circle"></i> RBI-Regulated Lending Partners</li>
								</ul>
							</div>
						</div>

						<div class="col-lg-6 col-md-6">
							<div class="choose_list">
								<ul>
									<li><i class="bi bi-check-circle"></i> Zero Unauthorized Data Access</li>
								</ul>
							</div>
						</div>

						<div class="col-lg-6 col-md-6">
							<div class="choose_list">
								<ul>
									<li><i class="bi bi-check-circle"></i> Transparent & Fair Processes</li>
								</ul>
							</div>
						</div>

						<div class="col-lg-6 col-md-6">
							<div class="choose_list">
								<ul>
									<li><i class="bi bi-check-circle"></i> Customer-First Loan Guidance</li>
								</ul>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</section>
<!--==================================================-->
<!-- End Why Choose Us Area-->
<!--==================================================-->

@endsection
